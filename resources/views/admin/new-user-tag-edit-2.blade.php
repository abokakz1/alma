<div class="tag-id-select form-group" style="margin-bottom: 12px;">
  <div class=" col-sm-offset-2  col-sm-5 col-input">
    <?php use App\Models\Tag;
        $tag_list = Tag::all();
    ?>
    <select name="tag_id_new[]" class="form-control" style="float: left;">
        <option value="0">Выберите тэг</option>
        @if(count($tag_list) > 0)
            @foreach($tag_list as $key => $tag_item)
                <option value="{{$tag_item->tag_id}}" @if($tag_item->tag_id == $row->tag_id) selected @endif>{{$tag_item->tag_name}}</option>
            @endforeach
        @endif
    </select>
    <i class="fa fa-times remove-tag-icon" aria-hidden="true" onclick="deleteTag(0,this)"></i>
  </div>
</div>