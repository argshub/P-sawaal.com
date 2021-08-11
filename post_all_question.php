<?php


$mysql = new mysqli("127.0.0.1", "root", "", "mysql");
$mysql->query("create database if not exists sawaal character set utf8 collate utf8_general_ci");
$mysql = new mysqli("127.0.0.1", "root", "", "sawaal");
$mysql->query("create table if not exists sawaal_questions (id int auto_increment primary key, questionLabel text unique, questionTitle longtext,questionTags text,questionExplanation longtext )");
$mysql->query("create table if not exists sawaal_questions_answers (id int auto_increment primary key, questionId int, answer longtext,isCorrect int default 0)");



$data = json_decode(file_get_contents("php://input"), true);
$questionLabel = $mysql->real_escape_string($data['questionLabel']);
$questionTitle = $mysql->real_escape_string($data['questionTitle']);
$questionTags = $mysql->real_escape_string($data['questionTags']);
$questionExplanation = $mysql->real_escape_string($data['questionExplanation']);
$questionOptions = $data['options'];
$mysql->query("insert into sawaal_questions (questionLabel, questionTitle, questionTags, questionExplanation) value ('{$questionLabel}', '{$questionTitle}', '{$questionTags}', '{$questionExplanation}')");

$questionId = $mysql->insert_id;
if($questionId) {
    if(count($questionOptions)) {
        $correctAnswer = strtolower($questionOptions[count($questionOptions) - 1]);
        switch ($correctAnswer) {
            case 'a':
                $correctAnswer = 0;
                break;
            case 'b':
                $correctAnswer = 1;
                break;
            case 'c':
                $correctAnswer = 2;
                break;
            case 'd':
                $correctAnswer = 3;
                break;
        }
        for($x = 0; $x < (count($questionOptions) - 1); $x++) {
            if($correctAnswer == $x) {
                $mysql->query("insert into sawaal_questions_answers (questionId, answer, isCorrect) value ({$questionId}, '{$questionOptions[$x]}', 1)");
            } else {
                $mysql->query("insert into sawaal_questions_answers (questionId, answer) value ({$questionId}, '{$questionOptions[$x]}')");
            }
        }
    }
}

$mysql->close();