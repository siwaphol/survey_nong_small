<?php

function addMainProgress($main_id, $progress_property ,$progress_no){
    $main = \App\Main::find($main_id);
    $progress = $main->progress;

    if (is_null($progress))
        $progress = array(
            'id'=>$main_id,
            \App\Main::PROGRESS_BUILDING=>array(),
            \App\Main::PROGRESS_INDUSTRY=>array()
        );

    if (!in_array((int)$progress_no, $progress[$progress_property]))
        array_push($progress[$progress_property], (int)$progress_no);

    $property = 'main.progress';
    session([$property=>$progress]);

    $main->progress = $progress;
    $main->save();
}

function getMainProgressLink($progressNo, $mainProp){
    $link = '';
    $progressNo = (int)$progressNo;

    // ถ้า step1 ควรจะเป็นแก้ หรือ create ใหม่ซึ่งไม่มี main_id
    if ($progressNo===1){
        $link .= Session::has('main.progress')&&in_array($progressNo, session('main.progress')[$mainProp])?('/'.session('main.id').'/edit'):'';
        return $link;
    }

    $link .= Session::has('main.id')?('/'.session('main.id')):'';
    $link .= Session::has('main.progress')&&in_array($progressNo, session('main.progress')[$mainProp])?'/edit':'';

    return $link;
}

function getMainProgressProp($progressNo, $placeType, $prop, $defaultProp=''){
    $progressNo = (int)$progressNo;

    $disabled = Session::has('main.progress')&&in_array($progressNo, session('main.progress')[$placeType])?$prop:$defaultProp;

    return $disabled;
}