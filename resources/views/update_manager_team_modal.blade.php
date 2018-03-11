<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

             	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title text-center" id="myModalLabel">Team Member Information</h4>

            </div>

            <form action="{{ route('user.managerUpdateTeam') }}" method="post" class="form-horizontal">

                <div class="modal-body">

	                <div class="form-group">

	                 	<label for="edit-name" class="col-sm-3 control-label">Name:</label>

                   		<div class="col-sm-9">
                            <input type="text" class="form-control" id="edit-name" name="name" value="">
                   		</div>

	                </div>

	                <div class="form-group">

                        <label for="edit-email" class="col-sm-3 control-label">Email:</label>

                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="edit-email" name="email" value="">
                        </div>

                    </div>

                    <div class="form-group">

                        <label for="edit-phone" class="col-sm-3 control-label">Phone:</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="edit-phone" name="phone" value="">
                        </div>

                    </div>

                </div>

                <div>

                    <input type="hidden" id="hidden-team-member-id" name="team_member_id" value="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                </div>

                <div class="modal-footer">

    	            <button type="submit" class="btn btn-primary" id="btn-save">Save changes</button>

                </div>

            </form>

        </div>

    </div>

</div>
