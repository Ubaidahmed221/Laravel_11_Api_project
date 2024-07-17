<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Laravel Posts</title>
  </head>
  <body>
   <div class="container">
    <div class="row">
        <div class="col-8 bg-primary text-white mb-4">
            <h1>All Posts</h1>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-8">
            <a href="/addposts" class="btn btn-sm btn-primary">Add New</a>
            <button class="btn btn-sm btn-danger" id="logoutbtn">Logout</button>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div id="postsContainer"></div>
        </div>
    </div>
   </div>

   <!-- single Post Modal -->
<div class="modal fade" id="singlepostmodel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="singlepostLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="singlepostLabel">Single Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

        </div>
      </div>
    </div>
  </div>
   <!-- Update Post Modal -->
<div class="modal fade" id="updatepostmodel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="updatepostLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updatepostLabel">Update Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateForm">
        <div class="modal-body">
            <input type="hidden" id="postId" class="form-control" value="">
           <b>Ttile</b> <input type="text" id="posttitle" class="form-control" value="">
           <b>Description</b> <input type="text" id="postdescription" class="form-control" value="">
           <img id="showimage" width="150px" >
            <b>Upload Image</b> <input type="file" id="postimage" class="form-control" value="">

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" value="Save Changes" class="btn btn-primary">
        </div>
    </form>
      </div>
    </div>
  </div>



    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
    <script>
        document.querySelector("#logoutbtn").addEventListener('click',function(){
            const token = localStorage.getItem('api_token');

            fetch('/api/logout',{
                method: "POST",
                headers:{
                    'Authorization':`Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                window.location.href = "http://127.0.0.1:8000/";
            });
        });

        function loadData(){
            const token = localStorage.getItem('api_token');
            fetch('/api/posts',{
                method: "GET",
                headers:{
                    'Authorization':`Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data => {

                var allposts = data.data.posts;
                const postConatiner = document.querySelector("#postsContainer");

           var tabledata = ` <table class="table table-bordered">
                    <tr class="table-dark">
                        <th>Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>View</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>`;
                    allposts.forEach(post => {
                        tabledata += `<tr>
                    <td><img src="/uploads/${post.image}" width="150px" /></td>
                    <td><h6>${post.title}</h6></td>
                    <td><p>${post.description}</p></td>
                    <td><button type="button" class="btn btn-sm btn-primary" data-bs-postid="${post.id}" data-bs-toggle="modal" data-bs-target="#singlepostmodel">View</button></td>
                    <td><button type="button" class="btn btn-sm btn-success" data-bs-postid="${post.id}" data-bs-toggle="modal" data-bs-target="#updatepostmodel" >Update</button></td>
                    <td><button type="button" onclick="deletepost(${post.id})" class="btn btn-sm btn-danger">Delete</button></td>
                    </tr>`;

                    });
                    tabledata +=`</table>`;
                    postConatiner.innerHTML = tabledata;
            });

        }

        loadData();

        // open Single Post Model
        var singleModel = document.querySelector("#singlepostmodel");
        singleModel.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget
        const ModelBody = document.querySelector("#singlepostmodel .modal-body");
        ModelBody.innerHTML = "";
        // Extract info from data-bs-* attributes
        var id = button.getAttribute('data-bs-postid')
        const token = localStorage.getItem('api_token');
            fetch(`/api/posts/${id}`,{
                method: "GET",
                headers:{
                    'Authorization':`Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.data.post[0]);
                const post = data.data.post[0];

                ModelBody.innerHTML = `
                Title : ${post.title}
                <br>
                Description : ${post.description}
                <br>
                <img width="150px" src="http://localhost:8000/uploads/${post.image}" />
                `;

            });
        })

        // open Update Post Model
        var updateModel = document.querySelector("#updatepostmodel");
        updateModel.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget

        // Extract info from data-bs-* attributes
        var id = button.getAttribute('data-bs-postid')

        const token = localStorage.getItem('api_token');

            fetch(`/api/posts/${id}`,{
                method: "GET",
                headers:{
                    'Authorization':`Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.data.post[0]);
                const post = data.data.post[0];

                document.querySelector("#postId").value = post.id;
                document.querySelector("#posttitle").value = post.title;
                document.querySelector("#postdescription").value = post.description;
                document.querySelector("#showimage").src = `/uploads/${post.image}`;

            });

        })

        // update form code
        document.addEventListener("DOMContentLoaded", function() {
    var updateForm = document.querySelector("#updateForm");

    updateForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        console.log('Form submission started');

        const token = localStorage.getItem('api_token');

        const postid = document.querySelector("#postId").value;
        const title = document.querySelector("#posttitle").value;
        const description = document.querySelector("#postdescription").value;


        var formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('id', postid);
        if(!document.querySelector("#postimage").files[0] == " "){
            const image = document.querySelector("#postimage").files[0];
            formData.append('image', image);

        }
        try {
            let response = await fetch(`/api/posts/${postid}`, {
                method: "POST",
                body: formData,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-HTTP-Method-Override' : 'PUT'
                }
            });
            let data = await response.json();
            console.log(data);
            window.location.href = "http://localhost:8000/allposts";

        } catch (error) {
            console.error('Error:', error);
        }
    });
});

// delete post code
 async function deletepost(postid){
    const token = localStorage.getItem('api_token');

    let response = await fetch(`/api/posts/${postid}`, {
                method: "DELETE",
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            let data = await response.json();
            console.log(data);
            window.location.href = "http://localhost:8000/allposts";

 }

    </script>
  </body>
</html>
