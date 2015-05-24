<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PaginatorClass
 *
 * @author jack
 */
class PaginatorClass {

    public $maxPage;
    public $LinkRange;
    public $CurrentPage;
    public $connection;

    public function __construct($db) {
        $this->connection = $db;
    }

    function getMaxPage() {
        return $this->maxPage;
    }

    function getLinkRange() {
        return $this->LinkRange;
    }

    function getCurrentPage() {
        return $this->CurrentPage;
    }

    function setMaxPage($maxPage) {
        $this->maxPage = $maxPage;
    }

    function setLinkRange($LinkRange) {
        $this->LinkRange = $LinkRange;
    }

    function setCurrentPage($CurrentPage) {
        $this->CurrentPage = $CurrentPage;
    }

    public function Results() {

        if (!$this->maxPage) {
            throw new Exception('Require maxPage number');
        } else {
            $amount = $this->CurrentPage * $this->maxPage;

            $query = "SELECT * FROM test LIMIT " . $amount . ',' . $this->maxPage;

            $result = $this->connection->query($query);
            $items = '';
            while ($row = $result->fetch_object()) {
                $items .= $row->id;
            }
            return $items;
        }
    }

    public function GenerateLinks() {

        if (!$this->CurrentPage) {
            throw new Exception('Current page is missing number');
        } else {
            $data = '';
            $query = "SELECT * FROM test";

            $Rowreturn = $this->connection->query($query);
            $number = $Rowreturn->num_rows;

            if (!$this->LinkRange) {
                throw new Exception('Range is not set');
            }

            $PageStart = $this->CurrentPage - $this->LinkRange;
            $PageEnd = $this->CurrentPage + $this->LinkRange;

            $Backwards = $this->CurrentPage - 1;
            $Forwards = $this->CurrentPage + 1;
            if ($this->CurrentPage != 1) {
                if ($this->CurrentPage > $number) {
                    throw new Exception('Page doesnt exist');
                } else {
                    $data .= '<li><a href="page=1">start</a></li>';
                    $data .= '<a href="'.$Backwards.'"><</a></li>';
                }
            }

            for ($i = $PageStart; $i < $PageEnd; $i++) {
                if ($number >= $i) {
                    if ($i == $this->CurrentPage) {
                        $data .= '<li><a href="page=' . $i . '">[' . $i . ']</a></li>';
                    } else {
                        $data .= '<li><a href="page=' . $i . '">[' . $i . ']</a></li>';
                    }
                }
            }

            $CheckNumber = ceil($number / $this->maxPage);

            if ($this->CurrentPage < $CheckNumber) {
                $data .= '<li><a href="'.$Forwards.'">></a></li>';
                $data .= '<li><a href="page=' . $CheckNumber . '">last</a></li>';
            }

            return $data;
        }
    }

    public function Links($class = array()) {
        $Count = count($class);
        $NewClass = '';
        foreach ($class as $count => $row) {
            $NewClass .= $row;
            if (!$Count == $count) {
                $NewClass .= ' ';
            }
        }
        if ($class > 0) {
            $ul = '<ul class="' . $NewClass . '">';
        } else {
            $ul = '<ul>';
        }

        $ul .= $this->GenerateLinks();

        $ul .= '</ul>';

        return $ul;
    }

}
