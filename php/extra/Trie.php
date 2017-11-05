<?php

/**
 * Class Trie
 * 前缀树搜索
 *
 * $trie = Trie::getInstance();
 * var_dump($trie->search('abcd'));
 * $trie->remove('abcd');
 * var_dump($trie->search('abcd'));
 * $trie->insert('abcd');
 * var_dump($trie->search('abcd'));
 *
 * $time = microtime(true);
 * for ($i = 0; $i < 10000; $i++) {
 * var_dump($trie->search('abc'));
 * }
 * echo microtime(true) - $time;
 */
class Trie
{
    public static $instance;

    public static $tree;

    private function __construct()
    {
        $this->init();
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (static::$instance instanceof self) {
            return static::$instance;
        }
        static::$instance = new self();
        return static::$instance;
    }

    private function init()
    {
        $arr = [
            'abcd',
            'abef',
            'achf',
            'baccc',
            'baeff',
            'edffds'
        ];

        foreach ($arr as $str) {
            $this->insert($str);
//            print_r(static::$tree);
        }

        /*
        $file = new SplFileObject('keywords');
        $i = 0;
        while (!$file->eof()) {
            $line = $file->fgets();
            echo $line;
            $this->insert(static::$tree, $line);

            $i++;
            if ($i > 10) {
                break;
            }
        }*/
    }

    /**
     * @param array $tree
     * @param string $str
     * @return null
     */
    public function insert($str, &$tree = null)
    {
        if ($str == '') {
            return null;
        }
        if (!is_array($tree)) {
            $tree = &static::$tree;
        }
        $length = mb_strlen($str, 'utf-8');
        if (1 == $length) {
            $tree[$str] = 1;
            return null;
        }
        $char = mb_substr($str, 0, 1, 'utf-8');
        if (isset($tree[$char])  && 1 == $tree[$char]) {
            return null;
        } else {
            $childStr = mb_substr($str, 1, $length - 1, 'utf-8');
            if (!isset($tree[$char])) {
                $tree[$char] = [];
            }
            $this->insert($childStr, $tree[$char]);
        }
    }

    /**
     * @param string $str
     * @param array|null $tree
     * @return bool
     */
    public function remove($str, &$tree = null)
    {
        if ($str == '') {
            return false;
        }
        if (!is_array($tree)) {
            $tree = &static::$tree;
        }
        $length = mb_strlen($str, 'utf-8');
        if (1 == $length) {
            if(isset($tree[$str])  && 1 == $tree[$str]) {
                unset($tree[$str]);
                return true;
            }
            return false;
        }
        $char = mb_substr($str, 0, 1, 'utf-8');
        if (!isset($tree[$char]) || 1 == $tree[$char]) {
            return false;
        }
        $childStr = mb_substr($str, 1, $length - 1, 'utf-8');
        if ($this->remove($childStr, $tree[$char]) && count($tree[$char]) == 0) {
            unset($tree[$char]);
            return true;
        }
        return false;
    }

    /**
     * @param string $search
     * @param array|null $tree
     * @return bool
     */
    public function search($search, &$tree = null)
    {
        if ($search == '') {
            return false;
        }
        if (!is_array($tree)) {
            $tree = &static::$tree;
        }
        $length = mb_strlen($search, 'utf-8');
        if (1 == $length) {
            return $tree[$search] == 1;
        }
        $char = mb_substr($search, 0, 1, 'utf-8');
        if (isset($tree[$char])  && 1 == $tree[$char]) {
            return true;
        } else {
            if (!isset($tree[$char])) {
                return false;
            }
            $childStr = mb_substr($search, 1, $length - 1, 'utf-8');
            return $this->search($childStr, $tree[$char]);
        }
    }
}
