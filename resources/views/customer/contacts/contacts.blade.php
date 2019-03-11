<div class="table-responsive">
    <table class="table table-hover table-striped">
    	  <thead>
    	  <tr>
    		  <th>Name</th>
    		   <th>Contact Number</th>
    			<th></th>
    	  </tr>
    	  </thead>
    	  <tbody>
          <?php $i = true; ?>
        @forelse($contacts as $contact)
        <tr>
            <td>{{$contact->name}}</td>
            <td>{{$contact->contact}}</td>
            <td><div class="checkbox"><label><input type="checkbox" name="contactid[]" class="customerCheckbox" value="{{$contact->id}}"></label></div></td>
        </tr>
        @empty
        <?php $i= false; ?>
        <tr>
            <td colspan="3">No Contact Found.</td>
        </tr>
        @endforelse
    	  </tbody>
	</table>
</div>
</div>
<div class="modal-footer">
      <div class="text-right">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
          <?php if ($i): ?>
          <button type="submit" class="btn btn-primary">Save</button>
        <?php endif; ?>
      </div>
