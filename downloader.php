<?php
class Downloader {
    static function download($uriSource, $destinyPath, array $options = array()){
        if(!isset($options['overwrite']) && file_exists($destinyPath) && filesize($destinyPath) > 1) { return; }
        if(!isset($options['proxyContext'])) { $options['proxyContext'] = null; }
        if(!is_dir(dirname($destinyPath))) { mkdir(dirname($destinyPath), 0777, true);  }
        try { $file = file_get_contents($uriSource, false, $options['proxyContext']); }
        catch (Exception $e) {}
        if($file === false) { throw new Exception("Error on downloading file from \"$uriSource\"", 1); }
        file_put_contents($destinyPath, $file);
    }

    static function getCachingContent($uri, $cachingDirPath = null, $context = null){
        $filepath = ($cachingDirPath === null) ? __DIR__.'/caches/' : $cachingDirPath;
        if(!is_dir($filepath)) { mkdir($filepath, 0777, true);  }
        $filename = "$filepath/".preg_replace('/[:\/\.]/', '', $uri);
        if(file_exists($filename)) { return file_get_contents($filename); }
        try {
            $result = file_get_contents($uri, false, $context);
        } catch (Exception $e) {}
        if($result === false) { throw new Exception("Error on downloading file from \"$uri\"", 1); }
        file_put_contents($filename, $result);
        return $result;
    }
}
