<?php


class Helper extends CController {

    public static function truncate_utf8_string($string, $length, $etc = '...') {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++) {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')) {
                if ($length < 1.0) {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            } else {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen) {
            $result .= $etc;
        }
        return $result;
    }

//远程图片传输
    public static function postdata($posturl, $data = array(), $upfileName = '', $file = '') {
        $url = parse_url($posturl);
        if (!$url)
            return "couldn't parse url";
        if (!isset($url['port']))
            $url['port'] = "";
        if (!isset($url['query']))
            $url['query'] = "";
        $boundary = "---------------------------" . substr(md5(rand(0, 32000)), 0, 10);
        $boundary_2 = "--$boundary";
        $content = $encoded = "";
        if ($data) {
            while (list($k, $v) = each($data)) {
                $encoded .= $boundary_2 . "\r\nContent-Disposition: form-data; name=\"" . rawurlencode($k) . "\"\r\n\r\n";
                $encoded .= rawurlencode($v) . "\r\n";
            }
        }
        if ($file) {
            $ext = strrchr($file, ".");
            $type = "image/jpeg";
            switch ($ext) {
                case '.gif': $type = "image/gif";
                    break;
                case '.jpg': $type = "image/jpeg";
                    break;
                case '.png': $type = "image/png";
                    break;
            }
            $encoded .= $boundary_2 . "\r\nContent-Disposition: form-data; name=\"$upfileName\"; filename=\"$file\"\r\nContent-Type: $type\r\n\r\n";
            $content = join("", file($file));
            $encoded.=$content . "\r\n";
        }
        $encoded .= "\r\n" . $boundary_2 . "--\r\n\r\n";
        $length = strlen($encoded);
        $fp = fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
        if (!$fp)
            return "Failed to open socket to $url[host]";

        fputs($fp, sprintf("POST %s%s%s HTTP/1.0\r\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
        fputs($fp, "Host: $url[host]\r\n");
        fputs($fp, "Content-type: multipart/form-data; boundary=$boundary\r\n");
        fputs($fp, "Content-length: " . $length . "\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $encoded);
        $line = fgets($fp, 1024);
        //if (!preg_match("^HTTP/1\.. 200u", $line))  //这儿的调试过不了，被我注释掉
        //  return null; 
        $results = "";
        $inheader = 1;
        while (!feof($fp)) {
            $line = fgets($fp, 1024);
            if ($inheader && ($line == "\r\n" || $line == "\r\r\n")) {
                $inheader = 0;
            } elseif (!$inheader) {
                $results .= $line;
            }
        }
        fclose($fp);
        return $results;
    }

   

}