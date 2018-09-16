<div class="tag-id-select" style="margin-bottom: 5px;">
    <?php use App\Models\Tag;
        $tag_list = Tag::all();
    ?>
    <select name="tag_id_new[]" style="float: left; width: 178px; ">
        <option value="0">Выберите тэг</option>
        @if(count($tag_list) > 0)
            @foreach($tag_list as $key => $tag_item)
                <option value="{{$tag_item->tag_id}}" @if($tag_item->tag_id == $row->tag_id) selected @endif>{{$tag_item->tag_name}}</option>
            @endforeach
        @endif
    </select>

    <i class="icon-remove red" style="float: left;margin: 7px 0px 0px 8px;" onclick="deleteTag(0,this)"></i>
    <div class="clearfloat"></div>
</div>