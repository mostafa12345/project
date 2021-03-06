<?php
require_once '../layout/header.php';
$defaulController = new DefaultController();

if ($_POST) {
    if (isset($_POST['edit'])) {
        $data = $_POST;
        unset($data['edit']);
        $result = $defaulController->update('post_response', $data);
        $msg = "اطلاعات با موفقیت ویرایش شد";
    } else if (isset($_POST['delete']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        $defaulController->remove('post_response', $id);
        $msg = "اطلاعات با موفقیت حذف شد";
    } else {
        $data = $_POST;
        $defaulController->insert('post_response', $data);
        $msg = "اطلاعات با موفقیت اضافه شد";
    }
}
$users = $defaulController->findAll('post_response');
?>
<?php if (isset($msg) && !empty($msg)): ?>
    <div class="alert">
        <?php echo $msg; ?>
        <button type="button" class="close" style="background-color: #18d596" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>
<div class="col-lg-12"><button class="pull-left btn btn-success" data-toggle="modal" data-target="#myModalCreate">Add</button></div>
<table class="display" id="test">
    <thead>
    <th>Id</th>
    <th>پست</th>
    <th>پاسخ</th>
    <th>کاربر ارسال کننده</th>
    <th>صحیح</th>
    <th>action</th>
</thead>
<tbody>
    <?php foreach ($users as $value) { ?>
        <tr>
            <td><?php echo $value['id']; ?></td>
            <td><?php
                if (!empty($value['post_id'])) {
                    $post = $defaulController->find('post', $value['post_id']);
                    if (!empty($post)) {
                        echo $post[0]['title'];
                    }
                }
                ?>
            </td>
            <td><?php echo $value['response']; ?></td>
            <td><?php
                if (!empty($value['user_id'])) {
                    $user = $defaulController->find('users', $value['user_id']);
                    if (!empty($user)) {
                        echo $user[0]['username'];
                    }
                }
                ?></td>
            <td>
                <?php if ($value['is_correct'] == true): ?>
                    <span style="color: green">بله</span>
                <?php else: ?>
                    <span style="color: red">خیر</span>
                <?php endif; ?>
            </td>
            <td>
                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#myModal<?php echo $value['id']; ?>">edit</button>
                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModalDelete<?php echo $value['id']; ?>">delete</button>
            </td>

            <!-- Modal -->
    <div class="modal fade" id="myModalDelete<?php echo $value['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $value['id']; ?>" />
                    <input type="hidden" name="delete" value="true" />
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <h3>آیا از حذف اطلاعات مطمئن هستید ؟</h3>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal<?php echo $value['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post">
                    <input type="hidden" name="edit" value="true" />
                    <input type="hidden" name="id" value="<?php echo $value['id'] ?>" />
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">ویرایش اطلاعات</h4>
                    </div>
                    <div class="modal-body">

                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <label> پست: </label>
                                    <select name="post_id" class="form-control">
                                        <?php
                                        $post_category = $defaulController->findAll('post');
                                        foreach ($post_category as $items) :
                                            ?>
                                            <option value="<?php echo $items['id']; ?>" <?php if ($value['id'] == $items['id']) echo 'selected'; ?>><?php echo $items['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <label>Response : </label>
                                    <textarea class="form-control" name="response" ><?php echo $value['response']; ?></textarea>
                                </div>
                                
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <label> کاربر سازنده : </label>
                                    <select name="user_id" class="form-control">
                                        <?php
                                        $users = $defaulController->findAll('users');
                                        foreach ($users as $items) :
                                            ?>
                                            <option value="<?php echo $items['id']; ?>" <?php if ($value['id'] == $items['id']) echo 'selected'; ?>><?php echo $items['username']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                    <label>صحیح : </label>
                                    <select name="is_correct" class="form-control">
                                        <option value="1" <?php if ($value['is_correct'] == 1) echo 'selected'; ?>>صحیح</option>
                                        <option value="0" <?php if ($value['is_correct'] == 0) echo 'selected'; ?>>غلط</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
    </tr>
<?php } ?>    
</tbody>
<tfoot>
<th>Id</th>
<th>پست</th>
<th>پاسخ</th>
<th>کاربر ارسال کننده</th>
<th>صحیح</th>
<th>action</th>

</tfoot>
</table> 

<div class="modal fade" id="myModalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">ویرایش اطلاعات</h4>
                </div>
                <div class="modal-body">

                    <div class="col-lg-12">
                        <div class="row">
                           <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label> پست: </label>
                                <select name="post_id" class="form-control">
                                    <?php
                                    $post = $defaulController->findAll('post');
                                    foreach ($post as $items) :
                                        ?>
                                        <option value="<?php echo $items['id']; ?>" ><?php echo $items['title']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label>Response : </label>
                                <textarea class="form-control" name="response" placeholder="Describtion" ></textarea>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label> کاربر سازنده : </label>
                                <select name="user_id" class="form-control">
                                    <?php
                                    $users = $defaulController->findAll('users');
                                    foreach ($users as $items) :
                                        ?>
                                        <option value="<?php echo $items['id']; ?>" ><?php echo $items['username']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                                <label>صحیح : </label>
                                <select name="is_correct" class="form-control">
                                    <option value="1" >بله</option>
                                    <option value="0" >خیر</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#test').DataTable();
    });
</script>
<?php
require_once '../layout/footer.php';
?>
