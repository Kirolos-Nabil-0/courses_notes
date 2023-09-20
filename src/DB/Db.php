<?php
namespace Kirolos\CoursesNotes\DB;
class db 
{
    protected $connection;
    protected $query;
    protected $show_errors = true;
    protected $query_closed = true;
    public $query_count = 0;

    public function __construct($dbhost = 'localhost', $dbuser = 'root', $dbpass = '', $dbname = 'courses_notes', $charset = 'utf8')
    {
        $this->connection = new \mysqli($dbhost, $dbuser, $dbpass, $dbname);
        if ($this->connection->connect_error) {
            $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
        }
        $this->connection->set_charset($charset);
    }

    public function query($query)
    {
        if (!$this->query_closed) {
            $this->query->close();
        }
        $this->query = $this->connection->query($query);
        if ($this->query === false) {
            $this->error('Unable to process MySQL query - ' . $this->connection->error);
        }
        $this->query_closed = false;
        $this->query_count++;
        return $this;
    }

    public function fetchArray()
    {
        $result = $this->query->fetch_assoc();
        if (!$result) {
            $this->query->close();
            $this->query_closed = true;
        }
        return $result;
    }

    public function fetchAll()
    {
        $result = $this->query->fetch_all();
        if (!$result) {
            $this->query->close();
            $this->query_closed = true;
        }
        return $result;
    }

    public function numRows()
    {
        return $this->query->num_rows;
    }

    public function affectedRows()
    {
        return $this->connection->affected_rows;
    }

    public function lastInsertID()
    {
        return $this->connection->insert_id;
    }

    public function escapeString($value)
    {
        return $this->connection->real_escape_string($value);
    }

    public function error($error)
    {
        if ($this->show_errors) {
            exit($error);
        }
    }
}
?>
