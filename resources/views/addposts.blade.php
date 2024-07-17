<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Laravel Posts</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-8 bg-primary text-white mb-4">
                <h1>Create Post</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-8">
                    <form  id="addform"  >
                        <input type="text" id="title" class="form-control mb-3" placeholder="Enter Title">

                        <textarea  id="description" class="form-control mb-3" placeholder="Enter Description"></textarea>

                        <input type="file"  id="image" class="form-control mb-3">

                        <input type="submit" class="btn btn-primary">

                        <a href="/allposts" class="btn btn-secondary">Back</a>

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
       document.addEventListener("DOMContentLoaded", function() {
    var addForm = document.querySelector("#addform");
    
    addForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        console.log('Form submission started');
        
        const token = localStorage.getItem('api_token');

        const title = document.querySelector("#title").value;
        const description = document.querySelector("#description").value;
        const image = document.querySelector("#image").files[0];

        var formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('image', image);
        try {
            let response = await fetch('/api/posts', {
                method: "POST",
                body: formData,
                headers: {
                    'Authorization': `Bearer ${token}`,
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


      </script>
</body>
</html>