<?php

$data = json_decode(file_get_contents("php://input"), true);
$mysql = new mysqli("localhost", "root", "", "pefinite");

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