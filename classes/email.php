<?php
use PHPMailer\PHPMailer\PHPMailer;


class Email{
    protected $db;
    protected $user_name;
    protected $user_email;


    function __construct($db_connection)
    {
        $this->db = $db_connection;
    }

    function sendRegistrationEmail($username, $email)
    {
        try {
            $this->user_name = trim($username);
            $this->user_email = trim($email);

            if (!empty($this->user_name) && !empty($this->user_email)) {

                    $check_email = $this->db->prepare("SELECT * FROM users WHERE user_email = ?");
                    $check_email->execute([$this->user_email]);

                    if ($check_email->rowCount() > 0) {
                        return ['errorMessage' => 'This email address is already registered!! Try another one!'];
                    } else {
                        $name = $this->user_name;
                        $email = $this->user_email;
                        $subject = "Registration Successful";
                        $body = "Hello " . $email . "!!, Your registration is successful. Please login http://localhost/SE/index.php";

                        require_once "PHPMailer/PHPMailer.php";
                        require_once "PHPMailer/SMTP.php";
                        require_once "PHPMailer/Exception.php";

                        $mail = new PHPMailer();

                        //SMTP Settings
                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->Username = "teamhcse@gmail.com"; //enter you email address
                        $mail->SMTPAuth = true;
                        $mail->Password = 'T3amH($3123'; //enter you email password
                        $mail->Port = 465;
                        $mail->SMTPSecure = "ssl";

                        //Email Settings
                        $mail->isHTML(true);
                        $mail->setFrom($email, $name);
                        $mail->addAddress("$email"); //enter you email address
                        $mail->Subject = ("$email ($subject)");
                        $mail->Body = $body;

                        if ($mail->send()) {
                            return ['successMessage' => 'You have signed up successfully. Please login'];
                        } else {
                            return ['errorMessage' => 'Invalid email address!'];
                        }
                    }

            }
            else {
                return ['errorMessage' => 'Please fill in all required fields.'];
            }

        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());
        }

    }

    function sendForgotPassEmail($email, $return)
    {
        try {

            $this->user_email = trim($email);
            if($return){
                if (!empty($this->user_email)) {

                    $email = $this->user_email;
                    $subject = "Password Reset";
                    $body = "Hello ".$email."!!, Your password reset link is here: http://localhost/SE/reset_password.php?id=$email";

                    require_once "PHPMailer/PHPMailer.php";
                    require_once "PHPMailer/SMTP.php";
                    require_once "PHPMailer/Exception.php";

                    $mail = new PHPMailer();

                    //SMTP Settings
                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->Username = "teamhcse@gmail.com"; //enter you email address
                    $mail->SMTPAuth = true;
                    $mail->Password = 'T3amH($3123'; //enter you email password
                    $mail->Port = 465;
                    $mail->SMTPSecure = "ssl";

                    //Email Settings
                    $mail->isHTML(true);
                    $mail->setFrom($email);
                    $mail->addAddress("$email"); //enter you email address
                    $mail->Subject = ("$email ($subject)");
                    $mail->Body = $body;

                    if ($mail->send()) {
                        return ['successMessage' => 'Please Check your email. Password reset link sent'];
                    } else {
                        return ['errorMessage' => 'Invalid email address!'];
                    }
                }

            }
            else {
                return ['errorMessage' => 'Please fill in your registered email.'];
            }

        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());
        }

    }

    function sendInviteMail($email)
    {
        try {
            $this->user_email = trim($email);
            if (!empty($this->user_email)) {
                if (filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) {
                    $check_email = $this->db->prepare("SELECT * FROM users WHERE user_email = ?");
                    $check_email->execute([$this->user_email]);

                    if ($check_email->rowCount() > 0) {
                        return ['errorMessage' => 'This email address is already registered!! Try another one!'];
                    } else {


                        $email = $this->user_email;
                        $subject = "Your Friend Invited you to use Eazy Roommate";
                        $body = "Hello " . $email . "!!, Your Friend Invited you to use Eazy Roommate. <h4>Link is here: http://localhost/SE/signup.php</h4>";

                        require_once "PHPMailer/PHPMailer.php";
                        require_once "PHPMailer/SMTP.php";
                        require_once "PHPMailer/Exception.php";

                        $mail = new PHPMailer();

                        //SMTP Settings
                        $mail->isSMTP();
                        $mail->Host = "smtp.gmail.com";
                        $mail->Username = "teamhcse@gmail.com"; //enter you email address
                        $mail->SMTPAuth = true;
                        $mail->Password = 'T3amH($3123'; //enter you email password
                        $mail->Port = 465;
                        $mail->SMTPSecure = "ssl";

                        //Email Settings
                        $mail->isHTML(true);
                        $mail->setFrom($email);
                        $mail->addAddress("$email"); //enter you email address
                        $mail->Subject = ("$email ($subject)");
                        $mail->Body = $body;

                        if ($mail->send()) {
                            return ['successMessage' => 'Invite sent successfully'];
                        } else {
                            return ['errorMessage' => 'Invalid email address!'];
                        }
                    }
                }

            }
        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());
        }

    }

    function sendRemindEmail($email, $umail)
    {
        try {

            $this->user_email = trim($email);

            if (!empty($this->user_email) && !empty($umail)) {

                $email = $this->user_email;
                $subject = "Reminder";
                $body = "Hello " . $email . "!!, This is a friendly reminder. Your friend " .$umail. " reminds you to settle up the expenses if you have any! Please Settle up here http://localhost/SE/profile.php";

                require_once "PHPMailer/PHPMailer.php";
                require_once "PHPMailer/SMTP.php";
                require_once "PHPMailer/Exception.php";

                $mail = new PHPMailer();

                //SMTP Settings
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->Username = "teamhcse@gmail.com"; //enter you email address
                $mail->SMTPAuth = true;
                $mail->Password = 'T3amH($3123'; //enter you email password
                $mail->Port = 465;
                $mail->SMTPSecure = "ssl";

                //Email Settings
                $mail->isHTML(true);
                $mail->setFrom($email);
                $mail->addAddress("$email"); //enter you email address
                $mail->Subject = ("$email ($subject)");
                $mail->Body = $body;

                if ($mail->send()) {
                    return ['successMessage' => 'Reminder Sent'];
                } else {
                    return ['errorMessage' => 'Invalid email address!'];
                }

            }
            else {
                return ['errorMessage' => 'Please fill in your registered email.'];
            }

        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());
        }

    }

    function sendExpenseAddedMail($email, $umail)
    {
        try {

            $this->user_email = trim($email);

            if (!empty($this->user_email) && !empty($umail)) {

                $email = $this->user_email;
                $subject = "Expense Added";
                $body = "Hello " . $email . "!! Your friend " .$umail. " added a new expense. Please check here http://localhost/SE/expense.php";

                require_once "PHPMailer/PHPMailer.php";
                require_once "PHPMailer/SMTP.php";
                require_once "PHPMailer/Exception.php";

                $mail = new PHPMailer();

                //SMTP Settings
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->Username = "teamhcse@gmail.com"; //enter you email address
                $mail->SMTPAuth = true;
                $mail->Password = 'T3amH($3123'; //enter you email password
                $mail->Port = 465;
                $mail->SMTPSecure = "ssl";

                //Email Settings
                $mail->isHTML(true);
                $mail->setFrom($email);
                $mail->addAddress("$email"); //enter you email address
                $mail->Subject = ("$email ($subject)");
                $mail->Body = $body;

                if ($mail->send()) {
                    return ['emailSuccessMessage' => 'Email Sent'];
                } else {
                    return ['emailErrorMessage' => 'Invalid email address!'];
                }

            }
            else {
                return ['emailErrorMessage' => 'Please fill in your registered email.'];
            }

        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());
        }

    }

    function sendExpenseUpdatedMail($email, $umail)
    {
        try {

            $this->user_email = trim($email);

            if (!empty($this->user_email) && !empty($umail)) {

                $email = $this->user_email;
                $subject = "Expense Updated";
                $body = "Hello " . $email . "!! Your friend " .$umail. " updated an expense expense. Please check here http://localhost/SE/activity.php";

                require_once "PHPMailer/PHPMailer.php";
                require_once "PHPMailer/SMTP.php";
                require_once "PHPMailer/Exception.php";

                $mail = new PHPMailer();

                //SMTP Settings
                $mail->isSMTP();
                $mail->Host = "smtp.gmail.com";
                $mail->Username = "teamhcse@gmail.com"; //enter you email address
                $mail->SMTPAuth = true;
                $mail->Password = 'T3amH($3123'; //enter you email password
                $mail->Port = 465;
                $mail->SMTPSecure = "ssl";

                //Email Settings
                $mail->isHTML(true);
                $mail->setFrom($email);
                $mail->addAddress("$email"); //enter you email address
                $mail->Subject = ("$email ($subject)");
                $mail->Body = $body;

                if ($mail->send()) {
                    return ['emailSuccessMessage' => 'Email Sent'];
                } else {
                    return ['emailErrorMessage' => 'Invalid email address!'];
                }

            }
            else {
                return ['emailErrorMessage' => 'Please fill in your registered email.'];
            }

        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());
        }

    }

}