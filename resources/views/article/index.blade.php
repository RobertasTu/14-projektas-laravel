@extends('layouts.app')

@section('content')

<div class="container">
    <div class="search-form row">
                <div class="col-md-8">
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" id="search-field" name="search-field"/>
            <button type="button" class="btn btn-primary" id="search-button" >Search</button>
            <span class="search-feedback">
            </span>
        </div>
    </div>
    <div class='search-alert'>
    </div>
    <div class="sort-form row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
           <select id="sortCol" name="sortCol">
                <option value='id' selected="true">ID</option>
                <option value='title'>Title</option>
                <option value='description'>Description</option>
                <option value='type_id'>Type</option>
            </select>

            <select id="sortOrder" name="sortOrder">
                <option value='ASC' selected="true">ASC</option>
                <option value='DESC'>DESC</option>
            </select>

            <select id="type_id" name="type_id">
                <option value="all" selected="true"> Show All </option>
            @foreach ($types as $type)
                <option value='{{$type->id}}'>{{$type->title}}</option>
            @endforeach
            </select>
                <button type="button" id="filterArticles" class="btn btn-primary">Filter Articles</button>

        </div>
    </div>

<div class="alerts d-none">
</div>


    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createArticleModal">
        Create New Article Modal
    </button>
{{--
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateArticleModal">
        Update Article Modal
    </button>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#showArticleModal">
        Show Article Modal
    </button> --}}

<table class="articles table table-striped">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Type</th>
        <th>Actions</th>
        <th></th>
    </tr>

    @foreach ($articles as $article)
        <tr class='rowArticle{{$article->id}}'>
            <td class='colArticleId'>{{$article->id}}</td>
            <td class='colArticleTitle'>{{$article->title}}</td>
            <td class='colArticleDescription'>{!!$article->description!!}</td>
            <td class='colArticleTypeTitle'>{{$article->articleType->title}}</td>
            <td>
                <button type="button" class="btn btn-success show-article" data-articleId='{{$article->id}}'>Show</button>
                <button type="button" class="btn btn-secondary update-article" data-articleId='{{$article->id}}'>Update</button>
            </td>
            <td><input class="delete-article" type="checkbox"  name="articleDelete[]" value="{{$article->id}}" /></td>
        </tr>
    @endforeach
</table>
<button class="btn btn-primary" id="delete-selected">Delete selected</button>
</div>
<div class="modal fade" id="createArticleModal" tabindex="-1" role="dialog" aria-labelledby="createArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create Article</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="articleAjaxForm">
                <div class="form-group row">
                    <label for="articleTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="articleTitle" type="text" class="form-control" name="articleTitle">
                        <span class="invalid-feedback articleTitle" role="alert"></span>
                    </div>

                </div>
                <div class="form-group row">
                    <label for="articleDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                    <div class="col-md-6">
                        <textarea id="articleDescription" name="articleDescription" class="summernote form-control">

                        </textarea>
                        <span class="invalid-feedback articleDescription" role="alert"></span>
                    </div>

                </div>
                <div class="form-group row articleType">
                    <label for="articleType" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                    <div class="col-md-6">

                        <select id="articleType" class="form-control" name="articleType">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}"> {{$type->title}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback articleType" role="alert"></span>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary addArticleModal">Add</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="showArticleModal" tabindex="-1" role="dialog" aria-labelledby="showArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title show-articleTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="show-articleDescription"></p>
          <p class="show-articleType"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="editArticleModal" tabindex="-1" role="dialog" aria-labelledby="editArticleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Article</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="articleAjaxForm">
                <input type='hidden' id='edit-articleid'>
                <div class="form-group row">
                    <label for="articleTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                    <div class="col-md-6">
                        <input id="edit-articleTitle" type="text" class="form-control" name="articleTitle">
                        <span class="invalid-feedback articleTitle" role="alert"></span>
                    </div>

                </div>

                <div class="form-group row">
                    <label for="articleDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                    <div class="col-md-6">
                        <textarea id="edit-articleDescription" name="articleDescription" class="summernote form-control">

                        </textarea>
                        <span class="invalid-feedback articleDescription" role="alert"></span>
                    </div>

                </div>
                <div class="form-group row articleType">
                    <label for="articleType" class="col-md-4 col-form-label text-md-right">{{ __('Type') }}</label>

                    <div class="col-md-6">

                        <select id="edit-articleType" class="form-control" name="articleType">
                            @foreach ($types as $type)
                                <option value="{{$type->id}}"> {{$type->title}}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback articleType" role="alert"></span>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary updateArticleModal">Update</button>
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
    function createTable(articles){
        $(".articles tbody").html("");
        $(".articles tbody").append("<tr><th>ID</th><th>Title</th><th>Description</th><th>Type</th><th>Actions</th><th></th></tr>");
        $.each(articles, function(key, article){
                var articleRow = "<tr class='rowArticle"+ article.id +"'>";
                    articleRow += "<td class='colArticleId'>"+ article.id +"</td>";
                    articleRow += "<td class='colArticleTitle'>"+ article.title +"</td>";
                    articleRow += "<td class='colArticleDescription'>"+ article.description +"</td>";
                    articleRow += "<td class='colArticleTypeTitle'>"+ article.typeTitle +"</td>";
                    articleRow += "<td>";
                    articleRow += "<button type='button' class='btn btn-success show-article' data-articleid='"+ article.id +"'>Show</button>";
                    articleRow += "<button type='button' class='btn btn-secondary update-article' data-articleid='"+ article.id +"'>Update</button>";
                    articleRow += "</td>";
                    articleRow += "<td>";
                    articleRow += "<input class='delete-article' type='checkbox'  name='articleDelete[]'' value='"+ article.id +"' />"
                    articleRow += "</td>";
                    articleRow += "</tr>";
                $(".articles tbody").append(articleRow);
        });
    }
    // $(document).ready(function() {
                $(document).on('click', '#delete-selected', function() {
            // $("#delete-selected").click(function() {
                var checkedArticles = [];
                $.each( $(".delete-article:checked"), function( key, article) {
                    checkedArticles[key] = article.value;
                });
                $.ajax({
                type: 'POST',
                url: '{{route("article.destroySelected")}}',
                data: { checkedArticles: checkedArticles },
                success: function(data) {
                        $(".alerts").toggleClass("d-none");
                        for(var i=0; i<data.messages.length; i++) {
                            $(".alerts").append("<div class='alert alert-"+data.errorsuccess[i] + "'><p>"+ data.messages[i] + "</p></div>")

                            var id = data.success[i];
                            if(data.errorsuccess[i] == "success") {
                                $(".article"+id ).remove();
                            }
                        }
                    }
                });
            })
            $(".delete-article").click(function(){
            var article_id = $(this).val();
        })
    // })

 $(document).ready(function() {
    $(".addArticleModal").click(function() {
        var articleTitle = $("#articleTitle").val();
        var articleDescription = $("#articleDescription").val();
        var articleType = $("#articleType").val();
        $.ajax({
                type: 'POST',
                url: '{{route("article.storeAjax")}}',
                data: {articleTitle:articleTitle, articleDescription:articleDescription, articleType:articleType },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#createArticleModal").modal("hide");
                        var articleRow = "<tr class='rowArticle"+ data.articleId +"'>";
                            articleRow += "<td class='colArticleId'>"+ data.articleId +"</td>";
                            articleRow += "<td class='colArticleTitle'>"+ data.articleTitle +"</td>";
                            articleRow += "<td class='colArticleDescription'>"+ data.articleDescription +"</td>";
                            articleRow += "<td class='colArticleTypeTitle'>"+ data.articleType +"</td>";
                            articleRow += "<td>";
                            articleRow += "<button type='button' class='btn btn-success show-article' data-articleid='"+ data.articleId +"'>Show</button>";
                            articleRow += "<button type='button' class='btn btn-secondary update-article' data-articleid='"+ data.articleId +"'>Update</button>";
                            articleRow += "</td>";
                            articleRow += "<td>";
                            articleRow += "<input class='delete-article' type='checkbox'  name='articleDelete[]'' value='"+ article.id +"' />"
                            articleRow += "</td>";
                            articleRow += "</tr>";
                        $(".articles").append(articleRow);
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div>");
                        $("#articleTitle").val('');
                        $("#articleDescription").val('');
                        $("#articleType").val('');
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
    $(document).on('click', '.show-article', function() {
    // $(".show-article").click(function() {
       $('#showArticleModal').modal('show');
       var articleId = $(this).attr("data-articleId");
       $.ajax({
                type: 'GET',
                url: '/articles/showAjax/' + articleId ,
                success: function(data) {
                    $('.show-articleTitle').html('');
                    $('.show-articleDescription').html('');
                    $('.show-articleType').html('');
                    $('.show-articleTitle').append(data.articleId + '. ' + data.articleTitle );
                    $('.show-articleDescription').append(data.articleDescription);
                    $('.show-articleType').append(data.articleType);
                }
            });
       console.log(articleId);
    });
    $(document).on('click', '.update-article', function() {
    // $(".update-article").click(function() {
        var articleid = $(this).attr('data-articleid');
        $("#editArticleModal").modal("show");
        $.ajax({
                type: 'GET',
                url: '/articles/editAjax/' + articleid ,
                success: function(data) {
                   $("#edit-articleid").val(data.articleId);
                  $("#edit-articleTitle").val(data.articleTitle);
                  $("#edit-articleDescription").val(data.articleDescription);
                  $("#edit-articleType").val(data.articleType);
                }
            });
    })

    $(".updateArticleModal").click(function() {
        var articleid = $("#edit-articleid").val();
        var articleTitle = $("#edit-articleTitle").val();
        var articleDescription = $("#edit-articleDescription").val();
        var articleType = $("#edit-articleType").val();
        $.ajax({
                type: 'POST',
                url: '/articles/updateAjax/' + articleid ,
                data: {articleTitle:articleTitle, articleDescription:articleDescription, articleType:articleType },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        $(".invalid-feedback").css("display", 'none');
                        $("#editArticleModal").modal("hide");
                        $(".alerts").append("<div class='alert alert-success'>"+ data.success +"</div");
                        $(".rowArticle"+ articleid + " .colArticleTitle").html(data.articleTitle);
                        $(".rowArticle"+ articleid + " .colArticleDescription").html(data.articleDescription);
                        $(".rowArticle"+ articleid + " .colArticleTypeTitle").html(data.articleType);

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

    $(document).on('input', '#search-field', function() {
        var searchField = $("#search-field").val();
        var searchFieldCount = searchField.length;
        if(searchFieldCount != 0 && searchFieldCount < 3) {
            $(".search-feedback").css('display', 'block');
            $(".search-feedback").html("Min 3 symbols");
        } else {
            $(".search-feedback").css('display', 'none');
        $.ajax({
                type: 'GET',
                url: '/articles/searchAjax/',
                data: {searchField: searchField },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        console.log(data.success);
                        $(".articles").css("display", "block");
                        $(".search-alert").html("");
                        $(".search-alert").html(data.success);
                        createTable(data.articles);
                    } else {
                        $(".articles").css("display", "none");
                        // $(".articles theader").html("");
                        $(".articles tbody").html("");
                        $(".search-alert").append("<div class='alert alert-danger'>"+ data.error +"</div>");
                        // $(".search-alert").html("");
                        // $(".search-alert").append(data.error);
                        console.log(data.error)
                    }
                }
            });
        }
    })
    $(document).on('click', '#filterArticles', function() {
        var sortCol = $("#sortCol").val();
        var sortOrder = $("#sortOrder").val();
        var type_id = $("#type_id").val();
        $.ajax({
                type: 'GET',
                url: '/articles/indexAjax/',
                data: {sortCol: sortCol, sortOrder: sortOrder, type_id: type_id },
                success: function(data) {
                    if($.isEmptyObject(data.error)) {
                        createTable(data.articles);
                    } else {
                        // $(".alerts").toggleClass("d-none");

                        console.log(data.error)
                    }
                }
            });
    });


 });
</script>
@endsection
