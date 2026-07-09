<div>
    <table class="table table-bordered">
          <tr>
            <th>S.No</th>
            <th>Feature</th>
            <th></th>
          </tr>

          @foreach ($this->featureRows as $index => $row)
            {{Form::hidden("feature_row[{$index}][id]",$row['id'])}}
            {{Form::hidden("feature_row[{$index}][plan_id]",$row['id'])}}
          <tr>
            <td>
              {{$index+1}}
            </td>
            <td>
              {{Form::hidden("feature_row[{$index}][id]",$row['id'])}}
              {{Form::hidden("feature_row[{$index}][plan_id]",$row['id'])}}
              {{Form::text("feature_row[{$index}][title]",$row['title'],['class'=>"form-control", "placeholder"=>'Enter Feature'])}}
            </td>
            
            <td>
              <button wire:click="removeRow({{ $index }})" type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
          @endforeach
          <tr>
            <td colspan="5" class="text-right">
              <button wire:click="addRow()" type="button" class="btn btn-success btn-sm" title="Delete Endorsement" {{$isDisableAddMoreBtn ? 'disabled' : ''}}>Add More</button>
            </td>
          </tr>
        </table>
</div>
