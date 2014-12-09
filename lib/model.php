<?php

class Model {

    public static $LIMIT = 10;

    public  $table = '';
    public  $name  = '';

    private $connection;
    private $error;
    private $sql;

    public $q_join = array();
    public $q_fields;
    public $q_where;
    public $q_order;
    public $q_group;
    public $q_limit;
    public $q_page;

    public $fields= '*';
    public $where = array();
    public $order = false;
    public $group = false;
    public $limit = false;
    public $page  = 1;

    public $validate = array();
    public $validateErrors = array();

    private $alias;
    private $meta;

    private static $inst = array();
    private static function getInstance() {
        $className = get_called_class();
        if (! isset(self::$inst[$className])) {
            $className = get_called_class();
            $modelName = preg_replace('/Model$/', '', $className);
            self::$inst[$className] = new $className($modelName);
        }
        return self::$inst[$className];
    }

    private function __construct($name) {
        $dbConfig = App::config('db');

        // create DB connection
        $this->connection = new mysqli( $dbConfig['host'],
                                        $dbConfig['username'],
                                        $dbConfig['password'],
                                        $dbConfig['database'],
                                        $dbConfig['port'] );
        if ($error = $this->connection->connect_error) {
            throw new Exception($error);
        }

        // set encoding
        $encoding = @$dbConfig['encoding'];
        $this->execute("SET NAMES " . ($encoding ? $encoding : 'utf8'));

        // save name
        $this->name = $name;
    }

    public static function options_for_select($value=null, $label=null, $conditions=array()) {
        $value = $value ? $value : 'id';
        $label = $label ? $label : 'name';

        $options = array();
        $data = self::build()
                    ->select(array($value, $label))
                    ->where($conditions)
                    ->query();
        foreach($data as $row) {
            $options[$row->$value] = $row->$label;
        }
        return $options;
    }

    public static function build() {
        $inst = self::getInstance();
        $inst->q_fields = $inst->fields;
        $inst->q_where  = $inst->where;
        $inst->q_order  = $inst->order;
        $inst->q_group  = $inst->group;
        $inst->q_limit  = $inst->limit;
        $inst->q_page   = $inst->page;

        return $inst;
    }

    public function join($modelName, $conditions=null, $type='INNER', $alias=null) {
        if (! $alias) {
            $alias = $modelName;
        }
        if (! $conditions) {
            $self = $this->alias();
            $conditions = "{$alias}.id = {$self}.".strtolower($modelName)."_id";
        }
        $this->q_join[$alias] = array(
            'model' => $modelName,
            'type' => $type,
            'conditions' => $conditions
        );

        return $this;
    }

    public function select($fields) {
        $this->q_fields = $fields;
        return $this;
    }

    public function where($where) {
        $this->q_where = $where;
        return $this;
    }

    public function order($order) {
        $this->q_order = $order;
        return $this;
    }

    public function group($group) {
        $this->q_group = $group;
        return $this;
    }

    public function limit($limit) {
        $this->q_limit = $limit;
        return $this;
    }

    public function page($page, $limit=null) {
        $this->q_page = $page;
        $this->q_limit = $limit===null ? self::$LIMIT : $limit;
        return $this;
    }

    public function createRecord() {
        $fields = $this->describe();
        $obj = array_combine($fields, array_fill(0, count($fields), ''));
        return (object)$obj;
    }

    public static function create($data=array()) {
        $record = self::getInstance()
                ->createRecord();
        if (! empty($data)) {
            foreach ($data as $k=>$v) {
                $record->$k = $v;
            }
        }

        return $record;
    }

    public static function get($id, $fields="*") {
        if (is_array($fields)) {
            $fields = implode(", ", $fields);
        }

        $inst = self::getInstance();
        if ($result = $inst->query_by_sql("SELECT {$fields} FROM {$inst->table} WHERE id={$id}")) {
            return (object)$result[0];
        }

        return false;
    }

    public function query($sql=null) {
        if ($sql !== null) {
            return $this->query_by_sql($sql);
        }

        $sql = array();

        // SELECT
        $q_fields = is_array($this->q_fields) ? $this->q_fields : array($this->q_fields);
        if (! empty($this->q_join)) {
            $q_fields = $this->labelizeSelect($q_fields);
        }
        $sql[] = "SELECT " . implode(', ', $q_fields);

        // FROM
        $sql[] = "FROM {$this->table} as " . $this->alias();

        // JOIN
        if (! empty($this->q_join)) {
            foreach ($this->q_join as $alias => $data) {
                $className = $data['model']."Model";
                eval("\$inst = ${className}::getInstance();");
                
                $sql[] = $data['type'] . " JOIN {$inst->table} as {$alias} ".
                        "ON " . $data['conditions'];
            }
        }

        // WHERE
        if (! empty($this->q_where)) {
            $sql[] = "WHERE " . $this->parseConditions($this->q_where);
        }

        // GROUP
        if ($this->q_group) {
            $sql[] = "GROUP BY {$this->q_group}";
        }

        // ORDER
        if ($this->q_order) {
            $sql[] = "ORDER BY {$this->q_order}";
        } elseif($this->order) {
            $sql[] = "ORDER BY {$this->order}";
        }

        // LIMIT
        if ($this->q_limit) {
            $limit  = (int)$this->q_limit;
            $offset = ($this->q_page-1) * $limit;
            $sql[] = "LIMIT {$offset}, {$limit}";
        }

        return $this->query_by_sql(implode(' ', $sql));
    }

    public function query_by_sql($sql) {
        $this->sql = $sql;
        $result = $this->connection->query($sql);
        if ($result) {
            $this->errorReset();
            $ret = array();
            while ($row = $result->fetch_assoc()) {
                $keys = array_keys($row);
                if (strstr($keys[0], ".")) {
                    $tmp = array();
                    foreach ($row as $k=>$v) {
                        list($model, $field) = explode(".", $k, 2);

                        if (! isset($tmp[$model])) {
                            $tmp[$model] = new StdClass();
                        }
                        $tmp[$model]->$field = $v;
                    }
                    $row = $tmp;
                }
                $ret[] = (object)$row;
            }
            $result->free();
            return $ret;
        } else {
            $this->errorLoad();
        }
        return false;
    }

    public function first($sql=null) {
        $result = $this->query($sql);
        return @$result[0];
    }

    public function validateData($data) {
        $defaultMessage = "Field %s is invalid.";

        // flatten rules
        $rules = $this->parseValidationRule($this->validate);

        $status = true;
        foreach ($rules as $r) {
            $rule    = $r['rule'];
            $field   = $r['field'];
            $message = $r['message'];

            if (! preg_match($rule, @$data[$field])) {
                $status = false;
                $this->validateErrors[$field] = sprintf($message, $field);
            }
        }
        return $status;
    }

    private function parseValidationRule($r, $forceField=false) {
        $rules = array();
        foreach($r as $field=>$v) {
            if ($forceField) {
                $field = $forceField;
            }

            if(is_array($v)) {
                if (isset($v[0])) {
                    $rules = array_merge($rules, $this->parseValidationRule($v, $field));
                } else {
                    $rules[] = array(
                        'field' => $field,
                        'rule'  => @$v['rule'],
                        'message' => isset($v['message']) ? $v['message'] : $defaultMessage
                    );
                }
            } else {
                $rules[] = array(
                    'field' => $field,
                    'rule'  => $v,
                    'message' => $defaultMessage
                );
            }
        }
        return $rules;
    }

    public static function update($fields, $conditions) {
        $inst = self::getInstance();

        $sql = "UPDATE {$inst->table} ".
               "SET ";
        $segments = '';
        foreach ($fields as $k=>$v) {
            $segments .= $inst->conditionsSegment($k, $v, ", ", false, true);
        }
        $sql .= preg_replace('/^\s*,\s*/', '', $segments)." ";

        $sql .= "WHERE " . $inst->parseConditions($conditions);

        return $inst->execute($sql);
    }

    public static function insert($data) {
        $inst = self::getInstance();

        $fields = array_keys($data);
        $values = array_values($data);

        $sql = "INSERT INTO {$inst->table}(`".implode('`, `', $fields)."`) ".
               "VALUES(";
        foreach ($values as $value) {
            $sql .= substr($inst->conditionsSegment('', $value, ',', false), 8).", ";
        }
        $sql = preg_replace('/,\s*$/', '', $sql).")";
        
        return $inst->execute($sql);
    }

    public static function save($data, $idField='id') {
        $inst = self::getInstance();
        if (! $inst->validateData($data)) {
            $inst->errorLoad(array(__('Uneti podaci nisu validni.')));
            return false;
        }

        $id = (int)@$data[$idField];
        unset($data[$idField]);
        if ($id>0) {
            return self::update($data, array(
                $idField => $id
            ));
        } else {
            return self::insert($data);
        }
    }

    public static function delete($conditions) {
        $inst = self::getInstance();
        $sql = "DELETE FROM {$inst->table} WHERE " . $inst->parseConditions($conditions);
        return $inst->execute($sql);
    }

    public static function validationErrors() {
        return self::getInstance()->validateErrors;
    }

    public function execute($sql) {
        $this->sql = $sql;
        if (! $res = $this->connection->query($sql)) {
            $this->errorLoad();
        }
        return $res ? true : false;
    }

    public static function lastInsertId() {
        return self::getInstance()
                ->connection->insert_id;
    }

    public static function sql() {
        return self::getInstance()->sql;
    }

    public static function error() {
        $inst  = self::getInstance();
        $error = $inst->error;
        if ($error) {
            $error->sql = $inst->sql;
        }
        return $error;
    }

    protected function errorLoad($errors = null) {
        if ($errors === null) {
            $this->error = (object)array(
                'code' => $this->connection->errno,
                'message' => $this->connection->error
            );
        } else {
            $this->error = (object)array(
                'message' => implode(' ', $errors)
            );
        }
    }

    protected function errorReset() {
        $this->error = false;
    }

    private function parseConditions($conditions, $glue='AND') {
        $where = '';

        foreach ($conditions as $k=>$v) {
            if (in_array(strtoupper($k), array('AND', 'OR'))) {
                $where .= " {$glue} (".$this->parseConditions($v, strtoupper($k)) . ")";
            } else {
                $where .= $this->conditionsSegment($k, $v, $glue);
            }
        }

        return preg_replace('/^\s*'.$glue.'\s*/i', '', trim($where));
    }

    private function conditionsSegment($field, $value, $glue = 'AND', $enclose=true, $set=false) {
        $encS = '(';
        $encE = ')';
        if (! $enclose) {
            $encS = $encE = '';
        }

        $sql = " {$glue} {$encS}`{$field}`";

        if (is_numeric($value)) {
            $sql .= " = {$value}";

        } elseif(is_bool($value)) {
            $sql .= " = " . ($v ? 1 : 0);

        } elseif(is_null($value)) {
            $sql .= $set ? " = NULL" : " IS NULL";

        } elseif(is_array($value)) {
            $sql .= " IN ('".implode("','", $value)."')";

        } else {
            if (preg_match('/(NOT\s)?LIKE\s*$/i', $field, $matches)) {
                $field = trim(preg_replace('/(NOT\s)?LIKE\s*$/i', '', $field));
                $sql   = " {$glue} {$encS}`{$field}` ". $matches[0] ." '" . $this->escape($value) . "'";

            } elseif(preg_match('/[<=>]+\s*$/', $field, $matches)) {
                $field = trim(preg_replace('/[<>]+=?\s*$/i', '', $field));

                if (! is_numeric($value)) {
                    $value = "'" . $this->escape($value) . "'";
                }

                $sql   = " {$glue} {$encS}`{$field}` " . $matches[0] . " " . $value;

            } else {
                $sql .= " = '" . $this->escape($value) . "'";
            }
        }
        return $sql . $encE;
    }

    private function alias() {
        if (! $this->alias) {
            $this->alias = $this->name;
        }
        return $this->alias;
    }

    private function labelizeSelect($fields, $as=false) {
        $lFields = array();
        foreach($fields as $field) {
            if (strstr($field, '.')) {
                $lFields[] = $field;
            } elseif ($field == '*') {
                $lFields = array_merge($lFields, $this->labelizeSelect($this->describe(), true));
                if (! empty($this->q_join)) {
                    foreach ($this->q_join as $alias => $data) {
                        $modelName = $data['model'];
                        eval("\$inst = {$modelName}Model::getInstance();");
                        $lFields = array_merge($lFields, $inst->labelizeSelect($inst->describe(), true));
                    }   
                }
            } else {
                $f = $this->alias().".{$field}";
                $lFields[] = $f . ($as ? " as `{$f}`" : '');
            }
        }

        return $lFields;
    }

    private function describe() {
        if (! $this->meta) {
            $this->meta = $this->query("DESC {$this->table}");
        }

        return array_map(function($a){
            return $a->Field;
        }, $this->meta);
    }

    private function escape($string) {
        return $this->connection->real_escape_string($string);
    }
}

?>