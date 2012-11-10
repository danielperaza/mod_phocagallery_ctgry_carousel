<?php/** * @license GNU/GPL, see LICENSE.php * @author Jan Linhart * @link http://escope.cz */class modPhocaGallery_ctgryHelper{    // Gest categories with first thumbnail - quicker way    function GetData($order, $limit, $catDisplay, $cats, $imagesLimit)    {        $db =& JFactory::getDBO();        $include = '';        //if category is string, make it array        if(is_string($cats)){            $cats = array(0 =>$cats);        }        if($catDisplay == 'subcategories') {            $include = 'AND pgcat.parent_id IN ('.implode(",", $cats).')';        }        if($catDisplay == 'selected') {            $include = 'AND pgcat.id IN ('.implode(",", $cats).')';        }        if($catDisplay == 'not_selected') {            $include = 'AND pgcat.id NOT IN ('.implode(",", $cats).')';        }        if($order != "RAND()")            {$order = 'pgcat.date '.$order;}                $query = 'SELECT                     pgcat.description cat_description,                     pgcat.title cat_title,                    pgcat.date cdate,                     pgcat.id cat_id,                     hits,                     pgcat.alias,                    (                        SELECT                             COUNT(com.id)                         FROM #__phocagallery_comments AS com                         WHERE com.catid = pgcat.id                    ) AS comments                FROM #__phocagallery_categories AS pgcat                WHERE pgcat.published = 1                AND pgcat.approved = 1                '.$include.'                ORDER BY '.$order.'                 LIMIT '.$limit;        $db->setQuery($query);        $categories = $db->loadObjectList();        for ($i = 0; $i < count($categories); $i++) {            $query = 'SELECT                        pg.extl,                         pg.extm,                         pg.exts,                         pg.title,                         pg.filename                                   FROM #__phocagallery AS pg                    WHERE pg.catid = '.(int)$categories[$i]->cat_id.'                    LIMIT '.$imagesLimit;            $db->setQuery($query);            $categories[$i]->images = $db->loadObjectList();            $query = 'SELECT COUNT(id) FROM #__phocagallery WHERE catid = '.(int)$categories[$i]->cat_id;            $db->setQuery($query);            $categories[$i]->amount = $db->loadResult();        }        return $categories;/**/    }}?>