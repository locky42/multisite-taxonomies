<?php

class DBHelper
{
    /**
     * @param string $table
     * @return string|null
     */
    public static function isTableExist(string $table): ?string
    {
        global $wpdb;
        return $wpdb->get_var("SHOW TABLES LIKE '$table'");
    }

    /**
     * @param string $table
     * @return array|string|string[]|null
     */
    public static function getOriginTable(string $table)
    {
        return preg_replace('/\d_/', '' ,$table);
    }

    /**
     * @param string $table
     * @param string $charset_collate
     * @return bool|int|mysqli_result|resource|null
     */
    public static function cloneOriginalTable(string $table, string $charset_collate = '')
    {
        $originTable = self::getOriginTable($table);
        if ($table == $originTable) {
            return false;
        }

        global $wpdb;

        $sql = "CREATE TABLE `$table` LIKE `$originTable`;";
        $result = $wpdb->query($sql);
        if ($result && $charset_collate) {
            $sql = "ALTER TABLE `$table` $charset_collate";
            $result = $wpdb->query($sql);

            $dbName = $wpdb->__get('dbname');
            $sqlColumns = "SELECT *
                        FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_SCHEMA = '$dbName' AND TABLE_NAME = '$originTable';";
            $columns = $wpdb->get_results($sqlColumns);
            foreach ($columns as $column) {
                if ($column->COLLATION_NAME && $column->COLLATION_NAME != $wpdb->collate) {
                    $sql = "ALTER TABLE $originTable MODIFY $column->COLUMN_NAME $column->COLUMN_TYPE CHARACTER SET $wpdb->charset COLLATE $wpdb->collate;";
                    $wpdb->query($sql);
                }
            }
        }
        return $result;
    }
}
