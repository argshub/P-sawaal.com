<?php

$mysql = new mysqli("127.0.0.1", "root", "", "mysql");
$mysql->query("create database if not exists sawaal character set utf8 collate utf8_general_ci");
$mysql = new mysqli("127.0.0.1", "root", "", "sawaal");
$mysql->query("create table if not exists sawaal_questions (id int auto_increment primary key, questionLabel text character set ascii collate ascii_general_ci, questionUri text, questionTitle longtext,questionTags text,questionExplanation longtext )");
$mysql->query("create table if not exists sawaal_questions_answers (id int auto_increment primary key, questionId int, answer longtext,isCorrect int default 0)");



$data = json_decode(file_get_contents("php://input"), true);

$questionLabel = $mysql->real_escape_string($data['questionLabel']);
$questionTitle = $mysql->real_escape_string($data['questionTitle']);
$questionTags = $mysql->real_escape_string($data['questionTags']);
$questionUri = $mysql->real_escape_string($data['questionUri']);
$questionExplanation = $mysql->real_escape_string($data['questionExplanation']);
$questionOptions = $data['options'];

if(!$mysql->query("select id from sawaal_questions where questionLabel = '{$questionLabel}'")->num_rows) {
    $mysql->query("insert into sawaal_questions (questionLabel, questionTitle, questionUri, questionTags, questionExplanation) value ('{$questionLabel}', '{$questionTitle}', '{$questionUri}', '{$questionTags}', '{$questionExplanation}')");
    $questionId = $mysql->insert_id;
    if($questionId) {
        if(count($questionOptions)) {
            $correctAnswer = mb_ord(strtolower($questionOptions[count($questionOptions) - 1])) - 97;
            for($x = 0; $x < (count($questionOptions) - 1); $x++) {
                $answerToSubmit = $mysql->real_escape_string($questionOptions[$x]);
                if($correctAnswer == $x) {
                    $mysql->query("insert into sawaal_questions_answers (questionId, answer, isCorrect) value ({$questionId}, '{$answerToSubmit}', 1)");
                } else {
                    $mysql->query("insert into sawaal_questions_answers (questionId, answer) value ({$questionId}, '{$answerToSubmit}')");
                }
            }
        }
    }
    print_r($questionId);
} else {
    print_r(0);
}


$mysql->close();
