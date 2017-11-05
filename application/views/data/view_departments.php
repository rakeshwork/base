
    <table class="table  table-striped table-bordered table-hover  table-condensed">

        <tr>
          <th class="text-info" >ID</th>
          <th class="text-info">Title</th>
          <th class="text-info">Web Link</th>
          <th class="text-info">EMAIL</th>
          <th class="text-info">CONTACT NUMBER</th>
          <th class="text-info">MESSAGE</th>
          <th class="text-info">PURPOSE</th>
          <th class="text-info">RECEIVED ON</th>
          <th class="text-info">ACTIONS</th>
        </tr>

        <?php foreach ( $aEnquiries as $key => $data ):?>

            <tr>
            <td> <?php echo ++$key ?> </td>
            <td> <?php echo $data->account_number ?></td>
            <td> <?php echo $data->firstname." ".$data->lastname ?> </td>
            <td> <?php echo $data->email ?> </td>
            <td> <?php echo $data->contact_number ?> </td>
            <td> <?php echo $data->message ?> </td>
            <td>
                <?php foreach ( $aEnquiry_purposes as $key => $purpose ):?>
                           <?php if( $data->purpose == $key ):?>
                                <?php echo $purpose; ?>
                           <?php endif;?>
               <?php endforeach;?>
           </td>
            <td> <?php echo date("d M Y",strtotime($data->created_on))?> </td>
            <td><label for="reply"
                       name="reply"
                       class="text-info"
                       data-toggle="modal"
                       data-target="#enq_reply"
                       data-enquiry="<?php echo $data->id ?>">
                </label><br>
                <a href="<?php echo base_url()?>contact_us/view_conversation?id=<?php echo $data->id ?>"
                   data-enquiry="<?php echo $data->id ?>"
                   name="view_conversation"
                   class="text-info">View Conversation</a>
            </td>
            </tr>

        <?php endforeach;?>

        <div class="container">
            <div class="modal fade" id="enq_reply" role="dialog">
                <div class="modal-dialog"  role="document">
                    <div class="modal-content">

                        <form action=""
                                class="reply_modal" id="postForm" role="form">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h3>Reply</h3>
                            </div>

                            <div class="modal-body" style="height : 200px">
                                <div class="form-group">
                                    <textarea placeholder="Give your message here..."
                                             id="message"
                                             name="message"
                                             class="form-control"
                                             style="height : 150px"></textarea>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <input type="submit"
                                       name="save"
                                       value="SAVE"
                                       class="btn btn-primary"
                                       id="save">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </table>
