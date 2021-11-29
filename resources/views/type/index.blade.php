@extends('layouts.app')

@section('content')

<div class="container">

<div class="alerts">
</div>


    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createTypeModal">
        Create New Type Modal
    </button>

<table class="types table table-striped">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Total Articles</th>
         <th>Actions</th>
         <th><th>
    </tr>

    @foreach ($types as $type)
        <tr class='type'>
            <td>{{$type->id}}</td>
            <td>{{$type->title}}</td>
            <td>{!!$type->description!!}</td>
            <td>{{$type->typeArticles->count()}}</td>
            <td>
                <button type="button" class="btn btn-success show-type" data-typeid='{{$type->id}}'>Show</button>
                <button type="button" class="btn btn-secondary update-type" data-typeid='{{$type->id}}'>Update</button>

            </td>
            <td><input class="delete-type" type="checkbox"  name="typeDelete[]" value="{{$type->id}}" /></td>
        </tr>
    @endforeach
</table>
<button class="btn btn-primary" id="delete-selected">Delete</button>
</div>
<div class="modal fade" id="createTypeModal" tabindex="-1" role="dialog" aria-labelledby="createTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="typeAjaxForm">
                <div class="form-group row">
                    <label for="typeTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="typeTitle" type="text" class="form-control" name="typeTitle">
                        <span class="invalid-feedback typeTitle" role="alert"></span>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="typeDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                    <div class="col-md-6">
                        <textarea id="typeDescription" name="typeDescription" class="summernote form-control">

                        </textarea>
                        <span class="invalid-feedback typeDescription" role="alert"></span>
                    </div>

                </div>

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary addTypeModal">Add</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="showTypeModal" tabindex="-1" role="dialog" aria-labelledby="showTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title show-typeTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="show-typeDescription"></p>
          <p class="show-typeArticles"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="editTypeModal" tabindex="-1" role="dialog" aria-labelledby="editTypeModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Type</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="typeAjaxForm">
                <input type='hidden' id='edit-typeid'>
                <div class="form-group row">
                    <label for="typeTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="edit-typeTitle" type="text" class="form-control" name="typeTitle">
                        <span class="invalid-feedback typeTitle" role="alert"></span>
                    </div>

                </div>

                <div class="form-group row">
                    <label for="typeDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                    <div class="col-md-6">
                        <textarea id="edit-typeDescription" name="typeDescription" class="summernote form-control">

                        </textarea>
                        <span class="invalid-feedback typeDescription" role="alert"></span>
                    </div>

                </div>

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary updateTypeModal">Update</button>
        </div>
      </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        }
    });
 $(document).ready(function() {
    $(".addTypeModal").click(function() {
        var typeTitle = $("#typeTitle").val();
        var typeDescription = $("#typeDescription").val();

        $.ajax({
                type: 'POST',
                url: '{{route("type.storeAjax")}}',
                data: {typeTitle:typeTitle, typeDescription:typeDescription },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createTypeModal").modal("hide");
                        $(".types").append("<tr class='type'><td>"+ data.typeId +"</td><td>"+ data.typeTitle +"</td><td>"+ data.typeDescription +"</td><td>" + 0 + "</td><td><button type='button' class='btn btn-success show-type' data-typeid='"+data.typeId+"'>Show</button><button type='button' class='btn btn-secondary update-type' data-typeid='"+data.typeId+"'>Update</button></td><td><input class='delete-type' type='checkbox'  name='typeDelete[]' value='"+data.typeId+"' /></td></tr>");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
                        $("#typeTitle").val('');
                        $("#typeDescription").val('');

                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    });
    $(".show-type").click(function() {
       $('#showTypeModal').modal('show');
       var typeid = $(this).attr("data-typeid");
       $.ajax({
                type: 'GET',
                url: '/types/showAjax/' + typeid ,
                success: function(data) {
                    $('.show-typeTitle').html('');
                    $('.show-typeDescription').html('');
                    $('.show-typeArticles').html('');
                    $('.show-typeTitle').append(data.typeId + '. ' + data.typeTitle );
                    $('.show-typeDescription').append(data.typeDescription);
                    $('.show-typeArticles').append(data.typeArticles);
                }
            });
       console.log(typeid);
    });
    $(".update-type").click(function() {
        var typeid = $(this).attr('data-typeid');
        $("#editTypeModal").modal("show");
        $.ajax({
                type: 'GET',
                url: '/types/editAjax/' + typeid ,
                success: function(data) {
                   $("#edit-typeid").val(data.typeid);
                  $("#edit-typeTitle").val(data.typeTitle);
                  $("#edit-typeDescription").val(data.typeDescription);
                 }
            });
    })
    $(".updateTypeModal").click(function() {
        var typeid = $("#edit-typeid").val();
        var typeTitle = $("#edit-typeTitle").val();
        var typeDescription = $("#edit-typeDescription").val();

        $.ajax({
                type: 'POST',
                url: '/types/updateAjax/' + typeid ,
                data: {typeTitle:typeTitle, typeDescription:typeDescription },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editTypeModal").modal("hide");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
                    } else {
                        $(".invalid-feedback").css("display", 'none');
                        $.each(data.error, function(key, error){
                            var errorSpan = '.' + key;
                            $(errorSpan).css('display', 'block');
                            $(errorSpan).html('');
                            $(errorSpan).append('<strong>'+ error + "</strong>");
                        });
                    }
                }
            });
    })
    $("#delete-selected").click(function() {
                var checkedTypes = [];
                $.each( $(".delete-type:checked"), function( key, type) {
                   checkedTypes[key] = type.value;
                });

                console.log(checkedTypes);
                   $.ajax({
                type: 'POST',
                url: '{{route("type.destroySelected")}}',
                data: { checkedTypes: checkedTypes },
                success: function(data) {
                        $(".alerts").toggleClass("d-none");
                        for(var i=0; i<data.messages.length; i++) {
                            $(".alerts").append("<div class='alert alert-"+data.errorsuccess[i] + "'><p>"+ data.messages[i] + "</p></div>")

                            var id = data.success[i];
                            if(data.errorsuccess[i] == "success") {
                                $(".type"+id ).remove();
                            }
                        }

                    }
                });
            })

        $(".delete-type").click(function(){
            var type_id = $(this).val();


         })

 });
</script>
@endsection
