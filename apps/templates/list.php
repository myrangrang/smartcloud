<div id="app-content">
        <div id="no_activities" class="hidden">
                <div class="body"> </div>
        </div>

        <div id="container">
		<?php 
		if (isset($_GET['ok'])) echo "<div> YAY.... </div>";
		?>
	 	<form action="post.php" method = "post">
	<?php //		message : <input type="text" name="message"> *to remove message add an empty one :)<br> ?>

<div class="well row">
        <div class="row">
            <div class="span4">
                <div class="control-group">
                    <div class="controls">
                        <label class="control-label" for="title"></label>
                        <input name="title" id="title" type="text" class="input-large" placeholder="Enter a title ..."><br>
                        <label class="control-label" for="message"></label>
                        <textarea class="input-large" name="message" id="message" rows="3" placeholder="Enter a message ..."></textarea>*to remove message add an empty one :)
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox" for="closeButton">
                            <input name="closeButton" id="closeButton" type="checkbox" value="checked" class="input-mini">Close Button
                        </label>
                    </div>
                    <div class="controls">
			Choose Group : <br>
                        <?php
			 $sql = 'SELECT * FROM `*PREFIX*group_user` WHERE uid = "' .\OCP\User::getUser(). '"';
        	        $query = \OCP\DB::prepare($sql);
                	$result = $query->execute();
			$i = 0;
        	        while($row = $result->fetchRow()) {
				echo '<input type="checkbox" name="group[]" value="'.$row['gid'].'">' . $row['gid']. '<br>';
			}

			?>
                    </div>
                </div>
            </div>

            <div class="span2">
                <div class="control-group" id="toastTypeGroup">
                    <div class="controls">
                        <label>Message Type</label>
                        <label class="radio">
                            <input type="radio" name="type" value="success" checked="">Success
                        </label>
                        <label class="radio">
                            <input type="radio" name="type" value="info">Info
                        </label>
                        <label class="radio">
                            <input type="radio" name="type" value="warning">Warning
                        </label>
                        <label class="radio">
                            <input type="radio" name="type" value="error">Error
                        </label>
                    </div>
                </div>
                <div class="control-group" id="positionGroup">
                    <div class="controls">
                        <label>Position</label>
                        <label class="radio">
                            <input type="radio" name="position" value="toast-top-right" checked="">Top Right
                        </label>
                        <label class="radio">
                            <input type="radio" name="position" value="toast-bottom-right">Bottom Right
                        </label>
                        <label class="radio">
                            <input type="radio" name="position" value="toast-bottom-left">Bottom Left
                        </label>
                        <label class="radio">
                            <input type="radio" name="position" value="toast-top-left">Top Left
                        </label>
                        <label class="radio">
                            <input type="radio" name="position" value="toast-top-full-width">Top Full Width
                        </label>
                        <label class="radio">
                            <input type="radio" name="position" value="toast-bottom-full-width">Bottom Full Width
                        </label>
                    </div>
                </div>
            </div>

            <div class="span3">
                <div class="control-group">
                    <div class="controls">
                        <label class="control-label" for="timeOut">Time out</label>
                        <input name="timeout" id="timeOut" type="text" placeholder="ms" class="input-mini" value="5000">

                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 25px;">
            <pre id="toastrOptions"></pre>
        </div>
    </div>
				
		<input type="submit">
		</form>
        </div>
        <div id="loading_activities" class="icon-loading"></div>

</div>


