<?php

class Expenses
{
    protected $db;
    protected $desc;
    protected $amount;
    protected $user1;
    protected $user2;
    protected $amount1;
    protected $amount2;
    protected $paid_by;

    function __construct($db_connection)
    {
        $this->db = $db_connection;
    }

    function insertExpense($desc, $paid_by, $share_with, $amount, $split)
    {


        if($split=="you_equally")
        {
            $this->desc = $desc;

            $this->user1 = $paid_by;
            $this->user2 = $share_with;

            $this->amount = $amount;

            $this->amount1 = $amount / 2;
            $this->amount2 = $amount / 2;

            $this->paid_by = $paid_by;


        }
        if($split=="them_equally")
        {

            $this->paid_by = $share_with;
            $this->desc = $desc;

            $this->user1 = $share_with;
            $this->user2 = $paid_by;
            $this->amount = $amount;

            $this->amount1 = $amount / 2;
            $this->amount2 = $amount / 2;
        }
        if ($split=="they_owe")
        {

            $this->desc = $desc;

            $this->user1 = $paid_by;
            $this->user2 = $share_with;
            $this->amount = $amount;

            $this->amount1 = $amount ;
            $this->amount2 = 0;

            $this->paid_by = $paid_by;
        }
        if ($split=="you_owe")
        {

            $this->paid_by = $share_with;
            $this->desc = $desc;

            $this->user1 = $share_with;
            $this->user2 = $paid_by;
            $this->amount = $amount;

            $this->amount1 = 0;
            $this->amount2 = $amount / 2;
        }




        if (!empty($this->desc) && !empty($this->amount) && !empty($this->user1) && !empty($this->user2)) {
            $sql = "INSERT INTO expense(exp_name,amount1,amount2,user1,user2,total_amount,paid_by) VALUES (:exp_name,:amount1,:amount2,:user1,:user2,:total_amount,:paid_by)";

            $register_stmt = $this->db->prepare($sql);
            $register_stmt->bindValue(':exp_name', $this->desc, PDO::PARAM_STR);
            $register_stmt->bindValue(':amount1', $this->amount1, PDO::PARAM_INT);
            $register_stmt->bindValue(':amount2', $this->amount2, PDO::PARAM_INT);
            $register_stmt->bindValue(':user1', $this->user1, PDO::PARAM_STR);
            $register_stmt->bindValue(':user2', $this->user2, PDO::PARAM_STR);
            $register_stmt->bindValue(':total_amount', $this->amount, PDO::PARAM_INT);
            $register_stmt->bindValue(':paid_by', $this->paid_by, PDO::PARAM_STR);

            $register_stmt->execute();

            return ['successMessage' => 'Expense added successfully.'];


        } else {
            return ['errorMessage' => 'Please fill the fields.'];
        }
    }

    function expensesRetrieval($uname){
        try{
            $sql = "SELECT * FROM expense WHERE user1=? OR user2 =?";
            $register_stmt = $this->db->prepare($sql);
            $register_stmt->execute([$uname,$uname]);
            if($register_stmt->rowCount() >0){
                return $register_stmt->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $errMsg){
            die($errMsg->getMessage());
        }
    }
    function expenseRetrieval($uId){
        try{
            $sql = "SELECT * FROM expense WHERE id=?";
            $register_stmt = $this->db->prepare($sql);
            $register_stmt->execute([$uId]);
            if($register_stmt->rowCount() === 1){
                return $register_stmt->fetch(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $errMsg){
            die($errMsg->getMessage());
        }
    }

    function settleExpense($description,$amount,$user){
        try{

        }
        catch(PDOException $errMsg){
            die($errMsg->getMessage());
        }
    }
}


