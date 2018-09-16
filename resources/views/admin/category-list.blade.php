@extends('admin.layout')

@section('content')
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0px;">
            <a href="/admin/category-edit/0">
                <input type="button" class="btn btn-primary" value="Добавить категорию" style="margin: 0px 0px 15px 0px;">
            </a>
            <div class="clearfloat"></div>
            <input type="button" class="btn btn-primary" value="Удалить отмеченные" style="line-height: normal; padding: 2px 15px; float: right; margin-bottom: 10px; margin-top: 0px;" onclick="deleteSelectedRows('category-list')">
            <div class="clearfloat"></div>

            <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="center" style="width: 50px;"><input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addAllToArray(this)">ID</th>
                        <th class="center" style="width: 250px;">Заголовок (Каз)</th>
                        <th class="center" style="width: 250px;">Заголовок (Рус)</th>
                        <th class="center" style="width: 250px;">Заголовок (Анг)</th>
                        <th class="center" style="width: 80px;"></th>
                    </tr>
                </thead>

                @if(count($row) > 0)
                    @foreach($row as $key => $category_item)
                        <tr class="row_{{$category_item->category_id}}">
                            <td class="center">
                                <input type="hidden" value="{{$category_item->category_id}}" class="hidden-id-input">
                                <input type="checkbox" style="margin-top: -2px; margin-right: 5px;" onclick="addToArray({{$category_item->category_id}},this)" class="checkbox-item-list">
                                <a href="/admin/category-edit/{{$category_item->category_id}}">{{$category_item->category_id}}</a>
                            </td>
                            <td><a href="/admin/category-edit/{{$category_item->category_id}}">{{$category_item->category_name_kz}}</a></td>
                            <td>{{$category_item->category_name_ru}}</td>
                            <td>{{$category_item->category_name_en}}</td>
                            <td style="padding-left: 20px;">
                                <div style="margin: 0px 0px 5px 0px;">
                                    <input type="button" class="btn btn-small btn-danger operation-classes" style="padding: 0px 20px;" onclick="deleteCategory({{$category_item->category_id}},this)" value="Удалить">
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
    </div>

    <script>
        function deleteCategory(category_id,ob){
            if (!confirm('Вы действительно хотите удалить категорию №' + category_id +'?')) {
                return false;
            }

            $.ajax({
                type: 'GET',
                url: "/admin/delete-category",
                data: {_token: CSRF_TOKEN, category_id: category_id},
                success: function(data){
                    if(data.result == false){
                        alert("Ошибка при удалении категории");
                    }
                    else{
                        showInfo("Категория #" + category_id + " удалена");
                        $(ob).closest("tr").remove();
                    }
                }
            });
        }
    </script>

@endsection

