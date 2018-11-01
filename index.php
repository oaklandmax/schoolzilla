<!DOCTYPE html>
<html>
    <head>
        <?
        require 'include/datasets_lib.php';
        use datasets\DataSetsClass;
        ?>
        <link rel='stylesheet' href='include/main.css'>
        <meta charset="UTF-8">
        <title>Data Transformation Demo</title>
    </head>
    <body>
        <?php
        require 'include/page_header.php';
        ?>
        <div id='content'>
            <?php
            $student_format_data_file = 'include/data/data_raw.csv'; // this is the CSV file that has the original sample data
            $student_data = new DataSetsClass; // read the file into a var
            $student_data->set_data_set_student($student_format_data_file); // format the data into arrays for use
            //print($student_data->render_class_data_as_table($student_data->get_data_set_student())); // show the data as a table
            
            echo '<h3>Raw student data from file include/data/data_raw.csv</h3><pre>';
            echo `cat include/data/data_raw.csv`;
            
            echo '</pre><h3>Student header array, so we know which data goes where</h3><pre>';
            print_r($student_data->get_data_header_student());
            
            echo '</pre><h3>Student data processed into array for reformatting</h3><pre>';
            print_r($student_data->get_data_set_student());
            // reformat the data
            $student_data->set_class_data_arr_from_student_data_arr();
            
            echo '</pre><h3>Validated class data array derived from student data array</h3><pre>';
            print_r($student_data->get_class_data_arr());

            // write student data to csv file for use in spreadsheet or other export
            $csv_file_name = 'include/data/new_student_data_by_class_score' . time() . '.csv'; // filename to write to
            echo '</pre><h3>Writing data to csv file: ' . $csv_file_name . '</h3><pre>';
            $student_data->write_data_to_csv($csv_file_name, $student_data->get_class_data_arr());
            echo `cat $csv_file_name`;
            echo '</pre>';
            echo '<h3>A table made from the new class data array to potentially allow user updates to data</h3>';

            print($student_data->render_class_data_as_table($student_data->get_class_data_arr()));
            ?>
        </div>
        <?php
        require 'include/page_footer.php';
        ?>
    </body>
</html>
