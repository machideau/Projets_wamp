<?php
// includes/admin.php

require 'db.php';

function getSkills() {
    global $pdo;
    return $pdo->query("SELECT * FROM skills")->fetchAll();
}

function getProjects() {
    global $pdo;
    return $pdo->query("SELECT * FROM projects")->fetchAll();
}

function addSkill($skill_name, $description) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO skills (skill_name, description) VALUES (:skill_name, :description)");
    $stmt->execute(['skill_name' => $skill_name, 'description' => $description]);
}

function addProject($title, $description, $image_url, $project_link) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO projects (title, description, image_url, project_link) VALUES (:title, :description, :image_url, :project_link)");
    $stmt->execute(['title' => $title, 'description' => $description, 'image_url' => $image_url, 'project_link' => $project_link]);
}
?>