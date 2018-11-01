<?php

namespace datasets;

class DataSetsClass {

    public $student_csv_raw = '';
    public $studen_data_arr_raw = '';
    public $student_header_arr = '';
    public $student_data_arr = '';
    public $class_data_arr = '';
    public $max_student_id_num = 8000000;

    function set_data_set_student($file_in) { //get the data from the spreadsheet
        $lines = file($file_in);
        $this->student_csv_raw = $lines;
        foreach ($lines as $key => $val) {
            if (!empty($val)) { // only add non-empty lines
                if ($key == 0) { // put headers into their own array
                    $this->student_header_arr = explode(',', $val);
                } else { // put everything else into another array
                    $tmp_array = explode(',', $val);
                    foreach ($tmp_array as $value) {
                        rtrim($value); // clean array elements of unwanted char
                    }
                    $this->student_data_arr[] = $tmp_array;
                }
            }
        }

        return true;
    }

    function get_student_csv_raw() {
        return $this->student_csv_raw;
    }

    function get_data_header_student() {
        return $this->student_header_arr;
    }

    function get_data_set_student() {
        return $this->student_data_arr;
    }

    function validate_student_data($dirty_array_in) { // this needs work.
        if ($dirty_array_in['0'] > $this->max_student_id_num) {
            // fail any student id over 8 million to remove invalid id per instruction
            return false;
        } elseif (is_numeric($dirty_array_in['1'])) {
            //subject names are strings
            return false;
        } elseif (!is_numeric($dirty_array_in['2'])) {
            // subject scores are numbers
            return false;
        } else {
            return true;
        }
    }

    function set_class_data_arr_from_student_data_arr() {
        // set headers on new data array
        $this->class_data_arr[] = array('student_id', 'subject', 'score');
        foreach ($this->student_data_arr as $key => $val) {
            $tmp_array = '';
            $i = '0';
            foreach ($val as $key2 => $val2) {
                $subject = '';
                // clean the headers, then assign them to the second field of the new array
                $subject = $this->student_header_arr[$i]; // get the subject for the second field
                $subject = preg_replace('/ Score.*/', '', $subject); // remove ' Score' from data field, just need subject name
                $subject = rtrim($subject); // remove any pesky newline chars and similar duff.
                $st_id = rtrim($val['0']);
                $val2 = rtrim($val2);
                if ($i > 0) { //since we set student id in $st_id above, we skip looping through the first element of the array
                    $tmp_array = array($st_id, $subject, $val2);
                    if ($this->validate_student_data($tmp_array)) { // validate data in tmp array before writing it to the class_data_arr
                        $this->class_data_arr[] = $tmp_array;
                    }
                }
                $i++;
            }
        }
        return true;
    }

    function get_class_data_arr() {
        return $this->class_data_arr;
    }

    function write_data_to_csv($file_name_out, $array_name_in) {
        //$file_name_out = $file_name_out . 'csv';
        $csvfile = fopen($file_name_out, 'w');
        foreach ($array_name_in as $value) {
            fputcsv($csvfile, $value);
        }
        fclose($csvfile);
    }

    function render_class_data_as_table($data_in) {
        $table_out = '';
        $table_out .= '<table id="table_out">';
        foreach ($data_in as $key => $value) {
            $table_out .= '<tr>';
            foreach ($value as $key2 => $val2) {
                $table_out .= '<td>' . $val2 . '</td>';
            }
            $table_out .= '</tr>';
        }
        $table_out .= '</table>';

        return $table_out;
    }

}
