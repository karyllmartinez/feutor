<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEUTOR - Welcome</title>
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            height: 100%;
        }


        .container {
            width: 750px;
            height: 750px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #007bff;
        }

        h2 {
            color: #2d7a41;
            font-size: 70px;
        }

        .role-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .role-button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #2d7a41;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .role-button:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function redirect(role) {
            if (role === "student") {
                window.location.href = "s-login.php";
            } else if (role === "student-tutor") {
                window.location.href = "t-registration.php";
            }
        }
    </script>
</head>

<body>
    <!-- <div class="container">
        <h1>FEUTOR</h1>
        <h2>WELCOME!</h2>
        <p>FEUTOR is a tutor booking website for FEU Roosevelt students.</p>
        <div class="role-buttons">
            <button class="role-button" onclick="redirect('student')">Student</button>
            <button class="role-button" onclick="redirect('student-tutor')">Student-Tutor</button>
        </div>
    </div> -->

    <table style="width: 100%; height: 100%; margin:0; display:flex;  ">
        <tbody style="width: 100%; height: 100%; display: flex; margin:0;">
            <tr style="width: 100%; height: 100%; background: linear-gradient(to top, #a6a6a6, white); margin:0;  display: flex; justify-content: center;
            align-items: center;">
                <td style="width: 100; height: 100%, margin:0; display: flex; justify-content: center;
            align-items: center; ">
                    <img src="icons/feutor-land.png" alt="logo" style="width: 70%; height: auto;  " loading="lazy">
                </td>
            </tr>
            <tr
                style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; background-color: #2d7a41">
                <td
                    style="width: 100; height: 100%, margin:0; display: flex; justify-content: center; align-items: center; background-color: #D9D9D9;">

                    <table style="width: 100%; height: 100%; margin:0; display: flex; background-color: #D9D9D9;">
                        <tbody style="width: 100%; height: 100%; display: flex; margin:0;">
                            <tr style="width: 100%; height: 100%; display: flex;">
                                <td style="width: 100%; height: 100%; display: flex;">
                                    <div class="container">

                                        <h2>WELCOME!</h2>
                                        <p style="font-size: 20px; font-weight: bold;">FEUTOR is a tutor booking website
                                            for FEU Roosevelt College students.</p>
                                        <div style="height: 100px;"></div>
                                        <p style="font-size: 20px; font-weight: bold;">SELECT YOUR ROLE:</p>
                                        <div style="height: 70px;"></div>
                                        <div>
                                            <table style="width: 100%; height: 100%; margin:0; display:flex;  ">
                                                <tbody style="width: 100%; height: 100%; display: flex; margin:0;">
                                                    <tr style="width: 100%; height: 100%; margin:0; display: flex;  justify-content: center; align-items: center; ">
                                                        <td style="width: 100; height: 100%, margin:0; display: flex; justify-content: center; align-items: center; ">
                                                            <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="icons/1.png" alt="logo" style="width: 40%; height: auto;" loading="lazy">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                        <div style="height: 10px;"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                        <button class="role-button" onclick="redirect('student')">Student</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        
                                                        </td>
                                                        
                                                    </tr>
                                                    
                                                    <tr style="width: 100%; height: 100%; margin:0;  display: flex; justify-content: center; align-items: center; ">
                                                        <td style="width: 100; height: 100%, margin:0; display: flex; justify-content: center; align-items: center; ">

                                                        <table>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <img src="icons/2.png" alt="logo" style="width: 40%; height: auto;  " loading="lazy">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                        <div style="height: 10px;"></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                        <button class="role-button" onclick="redirect('student-tutor')">Student-Tutor</button>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>



                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </td>
            </tr>
        </tbody>
    </table>


</body>

</html>