<?php class CI_Helper
{
    public function parse_answer_links($arr_check = null)
    {
        $str = '';
        $count = 0;
        if ($arr_check) {
            foreach ($arr_check as $key => $item) {
                if (!$item) {
                    $item = 0;
                } else {
                    $count++;
                }
                $str .= $key . '=' . $item . ', ';
            }
            rtrim($str, ' ,');
            if ($count == 0) {
                $str = '';
            }
        }
        return $str;
    }
}
