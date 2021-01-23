<?php


use Illuminate\Support\Str;

class ReadLgaCSV
{
  public function __construct()
  {
    $this->file = fopen(resource_path('/db_data/lgas.csv'), 'r');
    $this->delimiter = ",";
    $this->iterator = 0;
    $this->header = ['id', 'country_code', 'state_id', 'name', 'slug'];
  }

  public function csvToArray()
  {
    $data = [];
    while (($row = fgetcsv($this->file, 2000, $this->delimiter)) !== false) {
      $is_mul_1000 = false;
      if (!$this->header) {
        $this->header = $row;
      } else {
        $this->iterator++;
        $row[0] = (int) $row[0];
        $row[2] = (int) $row[2];
        $row[] = Str::slug($row[3] . '-' . $row[0]);
        $data[] = array_combine($this->header, $row);
        if ($this->iterator != 0 && $this->iterator % 2000 == 0) {
          $is_mul_1000 = true;
          $chunk = $data;
          $data = [];
          yield $chunk;
        }
      }
    }
    fclose($this->file);
    if (!$is_mul_1000) {
      yield $data;
    }
    return;
  }
}
