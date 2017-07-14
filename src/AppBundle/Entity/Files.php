<?php

namespace AppBundle\Entity;



class Files
{
    public $file;
    private  function  getCurrentDirectory($path)
    {
        return $path."/";
    }

    public  function  getAbsolutPath($path)
    {
        $replace = "src\\AppBundle\\Entity";
        $string = __DIR__;
        $string = str_replace($replace,"web/",$string);
        //return $string.$this->getCurrentDirectory($path);
        return $path;
        //return __DIR__.'/../../../../web/'.$this->getCurrentDirectory($path);
    }
    public  function  getAbsolutPath_other($path)
    {
        $replace = "src\\AppBundle\\Entity";
        $string = __DIR__;
       // $string = str_replace($replace,"web/",$string);
        //return $string.$path;
       return "/web/".$path;
       // return __DIR__.'/../../../../web/'.$path;
    }
    public  function move($filesource,$path)
    {
        move_uploaded_file($filesource,$this->getAbsolutPath($path));
    }

    function delete($directory,$path)
    {
        if(file_exists($this->getAbsolutPath($directory).$path))
        {
            unlink($this->getAbsolutPath($directory).$path);
        }
    }

    function add($directory,$path){
        //var_dump($this->file);
        $this->file->move($this->getAbsolutPath($directory),$path);
        return $this->getAbsolutPath($directory).$path;
    }

    public $initialpath = "upload/";
}
