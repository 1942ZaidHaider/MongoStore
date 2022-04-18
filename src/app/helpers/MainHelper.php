<?php
/**
 * Helper Class
 */
class MainHelper
{
    /**
     * Parse meta values in post data.
     *
     * @param array $post
     * @return void
     */
    public static function parseProduct($post){
        $arr=[];
        foreach ($post as $k => $v) {
            if (is_array($v)) {
                if ($k == "metaKey") {
                    foreach ($v as $K => $V) {
                        $arr["meta"][$V] = $post["metaVal"][$K];
                    }
                } elseif ($k != "metaVal") {
                    $arr[$k] = $v;
                }
            } else {
                $arr[$k] = $v;
            }
        }
        return $arr;
    }
}
