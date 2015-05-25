<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Paginate
 *
 * @author jack
 */
class Paginate {

    public $maxPage;
    public $CurrentPage;
    public $connection;
    private $LinkRange = 5;
    private $Class;
    private $dbcount;

    public function __construct($db) {
        $this->connection = $db;
    }

    function getMaxPage() {
        return $this->maxPage;
    }

    function getCurrentPage() {
        return $this->CurrentPage;
    }

    function setMaxPage($maxPage) {
        $this->maxPage = $maxPage;
    }

    function setCurrentPage($CurrentPage) {
        $this->CurrentPage = $CurrentPage;
    }

    public function query($Dbquery) {
        $query = "$Dbquery";
        $Rowreturn = $this->connection->query($query);
        if (!$Rowreturn) {
            throw new exception('Query is not valid');
        } else {
            $this->dbcount = $Rowreturn->num_rows;
        }
    }

    public function GenerateLi() {
        $PageStart = $this->CurrentPage - $this->LinkRange;
        $PageEnd = $this->CurrentPage + $this->LinkRange + 1;

        $data = '';
        for ($i = $PageStart; $i < $PageEnd; $i++) {
            if ($this->dbcount >= $i) {
                if ($i != 0) {
                    if ($i > 0) {
                        if ($i == $this->CurrentPage) {
                            $data .= '<li class="active"><a href="page=' . $i . '">[' . $i . ']</a></li>';
                        } else {
                            $data .= '<li><a href="page=' . $i . '">[' . $i . ']</a></li>';
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function AddClass($ulClass = array()) {
        $Count = count($ulClass) - 1;
        if (is_array($ulClass)) {
            $Data = '';
            foreach ($ulClass as $Counter => $row) {
                $Data .= $row;
                if ($Count !== $Counter) {
                    $Data .= ' ';
                }
            }
        } else {
            $Data = $ulClass;
        }
        $Data = 'class="' . $Data . '"';

        $this->Class = $Data;
    }

    private function BackWardLink() {
        if (!$this->CurrentPage) {
            throw new exception('Currentpage is missing');
        } else {
            if ($this->CurrentPage > $this->dbcount) {
                throw new exception('Invalid page');
            } else {
                if ($this->CurrentPage != 1) {
                    $BackwardLink = $this->CurrentPage - 1;
                    $First = '<li><a href="1">First</a></li>';
                    $First .= '<li><a href="' . $BackwardLink . '">One back</a></li>';
                    return $First;
                }
            }
        }
    }

    private function ForWardLink() {
        if (!$this->maxPage) {
            throw new exception('maxPage number missing');
        } else {
            $CheckNumber = ceil($this->dbcount / $this->maxPage);
            $ForwardLink = $this->CurrentPage + 1;

            if ($this->CurrentPage != $this->dbcount) {
                $Last = '<li><a href="' . $ForwardLink . '">one front</a></li>';
                $Last .= '<li><a href="' . $CheckNumber . '">Last</a></li>';
                return $Last;
            }
        }
    }

    public function GenerateUl() {

        $ul = "<ul $this->Class>";
        $ul .= $this->BackWardLink();
        $ul .= $this->GenerateLi();
        $ul .= $this->ForWardLink();
        $ul .= '</ul>';

        return $ul;
    }

    public function Result() {
        
    }

}
