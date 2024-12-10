<?php
session_start();

include('connection/dbconfig.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login System in PHP MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}
body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 0;
    font-family: "Poppins", sans-serif;

    /* Background image */
    background: url('icons/bg.jpg') no-repeat center center;
    background-size: cover;

    /* Green overlay */
    position: relative;
    z-index: 0;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(2, 101, 2, 0.698); /* Green overlay with low transparency */
    z-index: -1;

}

.box {
    position: relative;
    width: 500px;
    height: 550px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    padding: 10px;
}

.box::before{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent, #e2a705, #e2a705, #e2a705);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
}

.box::after{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent,#e2a705, #e2a705, #e2a705);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
    animation-delay: -3s;
}

.borderLine::before{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent, #e2a705, #e2a705, #e2a705);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
    animation-delay: -1.5s;
}

.borderLine::after
{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent,#e2a705, #e2a705, #e2a705);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
    animation-delay: -4.5s;
}

@keyframes animate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.box form{
    position: absolute;
    inset: 4px;
    background: #222;
    padding: 50px 40px;
    border-radius: 8px;
    z-index: 2;
    display: flex;
    flex-direction: column;
}

.box form h2{
    color: #fac614;
    font-weight: 500;
    text-align: center;
    letter-spacing: 0.1em;
    margin-top:-10px;
}
.box form p{
    color: #fff;
    font-weight: 500;
    text-align: center;
    letter-spacing: 0.1em;
    margin-top: px;
}


.box form .inputBox{
    position: relative;
    width: 300px;
    margin-top: 35px;
}

.box form .inputBox input{
    position: relative;
    width: 100%;
    padding: 20px 10px 10px;
    background: transparent;
    outline: none;
    border: none;
    box-shadow: none;
    color: #23242a;
    font-size: 1em;
    letter-spacing: 0.05em;
    transition: 0.5s;
    z-index: 10;
}

.box form .inputBox span{
    position: absolute;
    left: 0;
    padding: 20px 0px 10px;
    pointer-events: none;
    color: #8f8f8f;
    font-size: 1em;
    letter-spacing: 0.05em;
    transition: 0.5s;
}

.box form .inputBox input:valid ~ span,
.box form .inputBox input:focus ~ span {
    color: #fff;
    font-size: 0.75em;
    transform: translateY((-34px));
}

.box form .inputBox i{
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    transition: 0.5s;
    pointer-events: none;
}

.box form .inputBox input:valid ~ i,
.box form .inputBox input:focus ~ i {
    height: 44px;
}

.box form .links{
    display: flex;
    justify-content: space-between;
}

.box form .links a{
    margin: 10px 0;
    font-size: 0.75em;
    color: #8f8f8f;
    text-decoration: none;
}

.box form .links a:hover,
.box form .links a:nth-child(2){
    color: #fff;
}

.role-button {
    border: none;
    outline: none;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 1em;
    border-radius: 4px;
    font-weight: 600;
    background-color: #e2a705;
    margin-top: 10px;
}

.role-button:active {
    opacity: 0.8;
}

    </style>

<script>
        function redirect(role) {
            if (role === "student") {
                window.location.href = "s-login.php";
            } else if (role === "student-tutor") {
                window.location.href = "t-login.php";
            } else if (role === "admin") {
                window.location.href = "ad-login.php"; // PABAGO SA ADMIN!
            }
        }
    </script>
</head>

<body>
<div class="box">
        <span class="borderLine"></span>
        <form action="s-login.php" method="POST" class="body-reg">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="icons/feutor-land.png" alt="FEUTOR Logo" style="width: 100px; height: auto;">
        </div>
            <h2>WELCOME!</h2>
            <p>FEUTOR is a tutor booking website
                                            for FEU Roosevelt College students.</p>
            <p style="font-size: 20px; font-weight: bold; margin-bottom: -1px; margin-top: 10px">SELECT YOUR ROLE:</p>

            <button class="role-button" type="button" onclick="redirect('student')">Student</button>
            <button class="role-button" type="button" onclick="redirect('student-tutor')">Student-Tutor</button>
            <button class="role-button" type="button" onclick="redirect('admin')">Admin</button>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>