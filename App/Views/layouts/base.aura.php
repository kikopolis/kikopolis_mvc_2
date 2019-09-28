<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{page_title}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    (@asset('style', 'css'))
</head>

<body>
    (@section::layouts.header)
    <div class="container justify-content-center align-items-center">
        <div class="row">
            <h4 class="col-12 text-center"><u>Limited html allowed</u></h4>
            {!! no_escape !!}
            (@function::countDaysFromBirth(12.04.1987))
            (@section::extend)
        </div>
        <div class="row">
            <h4 class="col-12 text-center"><u>No Html allowed</u></h4>
            <div class="col-9">
                <h1 class="text-center">{{ heading_title }}</h1>
                <p class="text-justify">{{ content }}</p>
                (@includes::layouts.pointless.section-main)
                <div class="row">
                    <!-- TODO: Write limits and pagination for loops -->
                    <!-- TODO: Write limits and pagination for loops -->
                    <!-- TODO: Write limits and pagination for loops -->
                    <!-- TODO: Write limits and pagination for loops -->
                    <!-- TODO: Write limits and pagination for loops -->
                    <!-- TODO: Write limits and pagination for loops -->
                    <div class="col-12 border border-danger">
                        (@for::user in users)
                        <h4 class="text-danger">User data</h4>
                        <span>User ID : {!! user.id !!}</span><br>
                        <span>User name : {!! user.first_name !!} {!! user.first_name !!}</span><br>
                        <span>User email : {!! user.email !!}</span><br>
                        <img src="{{ user.image }}" alt=""><br>
                        (@endfor)
                    </div>
                    <div class="col-12 border border-danger">
                        (@for::post in posts)
                        <h4 class="text-danger">Post info</h4>
                        <span>Post ID : {!% post.post_id %!}</span><br>
                        <span>Post title : {!% post.post_title %!}</span><br>
                        <span>Post body : {!% post.post_body %!}</span><br>
                        <span>Post tags : {!% post.post_tags %!}</span><br>
                        <img src="{!% post.post_image %!}" alt=""><br>
                        <span>Post author : {!% post.post_author_name %!}</span><br>
                        <span>Posted on : {!% post.post_created_at %!}</span><br>
                        <span>Modified on : {!% post.post_modified_at %!}</span><br>
                        (@endfor)
                    </div>
                    <div class="col-12 border border-danger">
                        (@for::num in 0..32)
                        {{num}}
                        (@endfor)
                    </div>
                    <div class="col-12 border border-danger">
                        (@for::letter in a..z)
                        {{letter}}
                        (@endfor)
                    </div>
                    <div class="col-12 border border-danger">
                        (@for::team in teams)
                        <h5 class="text-center">{{team}}</h5><br>
                        (@endfor)
                    </div>
                    <div class="col-12 border border-danger">
                        <h4 class="text-danger">User data with keys</h4>
                        (@for::user in users)
                        (@for::key, value in user)
                        <span>User {{key}} : {!! value !!}</span><br>
                        (@endfor)
                        (@endfor)
                    </div>
                    <div class="col-12 border border-danger">
                        (@if::cars is same as [])
                        <h4 class="text-danger text-center">Cars === []</h4>
                        (@elseif::cars)
                        <h1 class="text-warning bg-dark text-center">Cars isset()</h1>
                        (@endif)

                        (@if::not drivers)
                        <h1>No drivers</h1>
                        (@endif)

                        (@if::users)
                        <h4 class="text-danger text-center">Users isset()</h4>
                        (@endif)

                        (@if::cars is not [])
                        <h4 class="text-danger text-center">Cars is not []</h4>
                        (@endif)
                    </div>
                </div>
            </div>
            <div class="col-3">(@includes::layouts.pointless.sidebar)</div>
        </div>
        <div class="row justify-content-center">(@includes::layouts.footer)</div>
        (@asset('main', 'javascript'))
    </div>
</body>

</html>