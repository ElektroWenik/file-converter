<?php

namespace App\Helpers;

class XmlHelper {
    static public function ArrayToXml($array, &$content)
    {
        foreach ($array as $key => $value) {
            if (is_array($value))
                if (!is_numeric($key)) {
                    $subnode = $content->addChild("$key");
                    self::ArrayToXml($value, $subnode);
                } else {
                    $subnode = $content->addChild("record");
                    self::ArrayToXml($value, $subnode);
                }
             else
                 $content->addChild("$key", htmlspecialchars("$value"));

        }
    }
}