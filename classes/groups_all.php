<?php


class Group
{
    protected $db;
    protected $groupName;
    protected $desc;
    protected $uname;
    protected $gname;

    function __construct($db_connection)
    {
        $this->db = $db_connection;
    }


    function createGroups($group_name, $description, $name, $username)
    {
        try {
            $this->groupName = trim($group_name);
            $this->desc = trim($description);
            $this->groupMembers = $name;

            //number of group member inputs
            $number = count($_POST["name"]);

            //all the members who go into groups
            $groups = [$username];
            for ($i = 0; $i < $number; $i++) {
                if (trim($_POST["name"][$i] != '')) {
                    //$sql = "INSERT INTO tbl_name(name) VALUES('".mysqli_real_escape_string($connect, $_POST["name"][$i])."')";
                    //mysqli_query($connect, $sql);
                    array_push($groups, $this->groupMembers[$i]);
                }
            }
            //converting array to string
            $groups_values = implode(",", $groups);

            if (!empty($this->groupName) && !empty($this->desc)) {
                $check_group = $this->db->prepare("SELECT * FROM groups WHERE group_name = ?");
                $check_group->execute([$this->groupName]);

                if ($check_group->rowCount() > 0) {
                    return ['errorMessage' => 'This group name already exists!! Try another one!'];
                } else {

                    $sql = "INSERT INTO groups (group_name,description,grp_members) VALUES (:grp_name,:description,:grp_members)";

                    $register_stmt = $this->db->prepare($sql);
                    $register_stmt->bindValue(':grp_name', $this->groupName, PDO::PARAM_STR);
                    $register_stmt->bindValue(':description', $this->desc, PDO::PARAM_STR);
                    $register_stmt->bindValue(':grp_members', $groups_values, PDO::PARAM_STR);

                    $register_stmt->execute();


                    return ['successMessage' => 'Group created successfully.'];
                }

            } else {
                return ['errorMessage' => 'Please fill group name and description.'];
            }
        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());

        }
    }

    function allGroups($username)
    {
        try {
            $this->uname = trim($username);
            $sql_res = $this->db->prepare("SELECT id,group_name,grp_members FROM groups");
            $sql_res->execute();
            if($sql_res->rowCount() >0) {
                $all_rows = $sql_res->fetchAll(PDO::FETCH_OBJ);

                return $all_rows;
            }
            else{
                return false;
            }
        } catch (PDOException $errMsg) {
            die($errMsg->getMessage());
        }
    }

    function groupName($id){
        try{
            $sql_res = $this->db->prepare("SELECT group_name FROM groups WHERE id = ?");
            $sql_res->execute([$id]);
            if($sql_res->rowCount() >0){
                $get_group_name = $sql_res->fetch(PDO::FETCH_OBJ);
                return $get_group_name;
            }
            else{
                return false;
            }

        }
        catch (PDOException $errMsg){
            die($errMsg->getMessage());
        }
    }

    function retrieveGroups($username, $bool_value)
    {
        try {
            $this->uname = trim($username);
            $sql_res = $this->db->prepare("SELECT id,group_name,grp_members FROM groups");
            $sql_res->execute();
            if($sql_res->rowCount() >0) {
                $all_rows = $sql_res->fetchAll(PDO::FETCH_OBJ);
                $groupNames = [];
                $groupIds = [];
                foreach ($all_rows as $row) {
                    $g_name = $row->group_name;
                    $g_id = $row->id;
                    $name_explode = explode(",", $row->grp_members);
                    foreach ($name_explode as $name_user) {
                        if ($this->uname === $name_user) {
                            array_push($groupNames,$g_name);
                            array_push($groupIds,$g_id);
                        }
                    }

                }
                if($bool_value){
                    return $groupNames;
                }
                else{
                    return $groupIds;
                }

            }
            else{
                return false;
            }
        } catch (PDOException $errMsg) {
            die($errMsg->getMessage());
        }
    }

    function find_group_members($groupId){
        try{
            //$this->gname = trim($groupName);
            $get_users = $this->db->prepare("SELECT grp_members FROM groups WHERE id = ?");
            $get_users->execute([$groupId]);
            if($get_users->rowCount() >0){
                $get_all_users = $get_users->fetch(PDO::FETCH_OBJ);
                $all_group_users = [];
                foreach ($get_all_users as $row) {
                    $name_explode = explode(",", $row);
                    foreach ($name_explode as $name_user) {

                            array_push($all_group_users,$name_user);

                    }

                }
                return $all_group_users;
            }
            else{
                return false;
            }
        }
        catch (PDOException $errMsg) {
            die($errMsg->getMessage());
        }
    }


    function groupExpense($groupId,$group_exp_name, $description, $amount, $name, $paidBy)
    {
        try {
            $this->groupExpName = trim($group_exp_name);
            $this->description = trim($description);
            $this->amount = trim($amount);
            $this->groupMembers = $name;
            $this->paidBy = $paidBy;

            //number of group member inputs
            $number = count($_POST["name"]);
            $eachPay = $this->amount / ($number+1);
            //all the members who go into groups
            $groupsExp = [];
            for ($i = 0; $i < $number; $i++) {
                if (trim($_POST["name"][$i] != '')) {
                    //$sql = "INSERT INTO tbl_name(name) VALUES('".mysqli_real_escape_string($connect, $_POST["name"][$i])."')";
                    //mysqli_query($connect, $sql);
                    array_push($groupsExp, $this->groupMembers[$i]);
                }
            }

            //converting array to string
            $groups_values = implode(",", $groupsExp);

            if (!empty($this->groupExpName) && !empty($this->description)) {
                $check_group = $this->db->prepare("SELECT * FROM group_expense WHERE expense_name = ?");
                $check_group->execute([$this->groupExpName]);

                if ($check_group->rowCount() > 0) {
                    return ['errorMessage' => 'This group expense name already exists!! Try another one!'];
                } else {

                    $sql = "INSERT INTO group_expense (group_id,expense_name,description,amount,grp_members,paid_by,eachPay) VALUES (:group_id,:expense_name,:description,:amount,:grp_members,:paid_by,:eachPay)";

                    $register_stmt = $this->db->prepare($sql);
                    $register_stmt->bindValue(':group_id', $groupId, PDO::PARAM_INT);
                    $register_stmt->bindValue(':expense_name', $this->groupExpName, PDO::PARAM_STR);
                    $register_stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
                    $register_stmt->bindValue(':amount', $this->amount, PDO::PARAM_INT);
                    $register_stmt->bindValue(':grp_members', $groups_values, PDO::PARAM_STR);
                    $register_stmt->bindValue(':paid_by', $this->paidBy, PDO::PARAM_STR);
                    $register_stmt->bindValue(':eachPay', $eachPay, PDO::PARAM_INT);

                    $register_stmt->execute();


                    return ['successMessage' => 'Group Expense created successfully.'];
                }

            } else {
                return ['errorMessage' => 'Please fill group expense name and description.'];
            }
        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());

        }
    }

    function find_group_expense($groupId){
        try{

            $get_expenses = $this->db->prepare("SELECT group_id,expense_name,description,amount,paid_by,eachPay FROM group_expense WHERE group_id = ?");
            $get_expenses->execute([$groupId]);
            if($get_expenses->rowCount() >0){
                $get_all_expenses = $get_expenses->fetchAll(PDO::FETCH_OBJ);
                return $get_all_expenses;
            }
            else{
                return false;
            }
        }
        catch (PDOException $errMsg) {
            die($errMsg->getMessage());
        }
    }

    function find_group_expenses($exName){
        try{

            $get_expenses = $this->db->prepare("SELECT group_id,expense_name,description,amount,paid_by,eachPay FROM group_expense WHERE expense_name = ?");
            $get_expenses->execute([$exName]);
            if($get_expenses->rowCount() >0){
                $get_all_expenses = $get_expenses->fetchAll(PDO::FETCH_OBJ);
                return $get_all_expenses;
            }
            else{
                return false;
            }
        }
        catch (PDOException $errMsg) {
            die($errMsg->getMessage());
        }
    }

    function find_group_expense_members($groupId){
        try{
            //$this->gname = trim($groupName);
            $get_users = $this->db->prepare("SELECT grp_members FROM group_expense WHERE expense_name = ?");
            $get_users->execute([$groupId]);
            if($get_users->rowCount() >0){
                $get_all_users = $get_users->fetch(PDO::FETCH_OBJ);
                $all_group_users = [];
                foreach ($get_all_users as $row) {
                    $name_explode = explode(",", $row);
                    foreach ($name_explode as $name_user) {

                        array_push($all_group_users,$name_user);

                    }

                }
                return $all_group_users;
            }
            else{
                return false;
            }
        }
        catch (PDOException $errMsg) {
            die($errMsg->getMessage());
        }
    }

    function updateGroupExpense($group_exp_name, $description, $amount, $name, $paidBy)
    {
        try {
            $this->groupExpName = trim($group_exp_name);
            $this->description = trim($description);
            $this->amount = trim($amount);
            $this->groupMembers = $name;
            $this->paidBy = $paidBy;

            //number of group member inputs
            $number = count($_POST["name"]);

            $eachPay = $this->amount / ($number+1);
            //all the members who go into groups
            $groupsExp = [$paidBy];
            for ($i = 0; $i < $number; $i++) {
                if (trim($_POST["name"][$i] != '')) {
                    //$sql = "INSERT INTO tbl_name(name) VALUES('".mysqli_real_escape_string($connect, $_POST["name"][$i])."')";
                    //mysqli_query($connect, $sql);
                    array_push($groupsExp, $this->groupMembers[$i]);
                }
            }

            //converting array to string
            $groups_values = implode(",", $groupsExp);

            if (!empty($this->description) && !empty($this->amount)) {
                {
                    //UPDATE `group_expense` SET `expense_name` = 'nimmaa', `description` = 'nimmaaaa1', `amount` = '50000', `grp_members` = 'JohnSS,js1837,root' WHERE `group_expense`.`id` = 106;
                    $sql="UPDATE group_expense SET  description = '$description', amount = '$amount', grp_members='$groups_values', eachPay = '$eachPay' WHERE expense_name = ?";
                    //$sql = "INSERT INTO group_expense (group_id,expense_name,description,amount,grp_members,paid_by) VALUES (:group_id,:expense_name,:description,:amount,:grp_members,:paid_by)";

                    $register_stmt = $this->db->prepare($sql);


                    $register_stmt->execute([$this->groupExpName]);


                    return ['successMessage' => 'Expense updated successfully.'];
                }

            } else {
                return ['errorMessage' => 'Please fill all fields.'];
            }
        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());

        }
    }

    function settleGroupExpense($group_exp_name,$grpID)
    {
        try {
            $this->groupExpName = trim($group_exp_name);

            $sql="DELETE FROM group_expense WHERE expense_name = ?";

            $register_stmt = $this->db->prepare($sql);

            $register_stmt->execute([$this->groupExpName]);

            header('Location: groups_expense.php?id='.$grpID.'');


        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());

        }
    }

    function settleGroupExpenseNonPaid($group_exp_name,$groupData,$uname,$grpID)
    {
        try {
            $this->groupExpName = trim($group_exp_name);

            $groupsExp = [];




            foreach ($groupData as $i){
                if($uname !== $i){
                    array_push($groupsExp, $i);
                }
            }
            //converting array to string
            $groups_values = implode(",", $groupsExp);

            $sql="UPDATE group_expense SET grp_members='$groups_values' WHERE expense_name = ?";
            //$sql = "INSERT INTO group_expense (group_id,expense_name,description,amount,grp_members,paid_by) VALUES (:group_id,:expense_name,:description,:amount,:grp_members,:paid_by)";

            $register_stmt = $this->db->prepare($sql);


            $register_stmt->execute([$this->groupExpName]);
            header('Location: groups_expense.php?id='.$grpID.'');



        } catch (PDOException $errorMsg) {
            die($errorMsg->getMessage());

        }
    }
}