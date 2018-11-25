<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Course List</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fab fa-leanpub fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Course Name</th>
                    <th>Credits</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="course in courses" :key="course.id">
                    <td>{{course.id}}</td>
                    <td>{{course.title}}</td>
                    <td>{{course.credits}}</td>
                    <td>
                        <a href="#" @click="editModal(course)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteCourse(course.id)">
                            <i class="fa fa-trash-alt" style="color:red"></i>
                        </a>
                    </td>
                  </tr>                  
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" v-show="!editMode" id="exampleModalLabel">Add New Course</h5>
                <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Update Course</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateCourse() : CreateCourse()">
            <div class="modal-body">
                <div class="form-group">
                    <input v-model="form.title" type="text" name="title" placeholder="Title"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('title') }">
                    <has-error :form="form" field="title"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.credits" type="credits" name="credits" placeholder="Credits"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('credits') }">
                    <has-error :form="form" field="credits"></has-error>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button v-show="editMode" type="submit" class="btn btn-success">Update</button>
                <button v-show="!editMode" type="submit" class="btn btn-primary">Create</button>
            </div>
            </form>
            </div>
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editMode:false,
                courses: {},
                form: new Form({
                id:'',
                title: '',
                credits:''
                })
            }
        },
        methods: {
            updateCourse(){
                this.form.put('api/course/'+this.form.id)
                .then(()=>{
                    $('#addNew').modal('hide')
                    swal(
                            'Updated!',
                            'Your record has been updated.',
                            'success'
                            )
                            Fire.$emit('CourseEvent')
                })
                .catch(()=>{

                });
            },
            editModal(course){
                this.editMode=true;
                this.form.reset();
                $('#addNew').modal('show')
                this.form.fill(course)
            },
            newModal(){
                this.editMode=false;
                this.form.reset();
                $('#addNew').modal('show')
            },
            deleteCourse(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/course/'+id)
                        .then(()=>{                        
                            swal(
                            'Deleted!',
                            'Your record has been deleted.',
                            'success'
                            )
                            Fire.$emit('CourseEvent')                    
                        })
                        .catch(()=>{
                            swal("Failed","There was something wrong","warning");
                        });  
                    }                  
                    })
            },
            LoadCourses(){
                axios.get("api/course").then(({ data }) => (this.courses=data.data))
            },
            CreateCourse () {
            // Submit the form via a POST request
            this.form.post('api/course')
            .then(()=>{
                Fire.$emit('CourseEvent')
                $('#addNew').modal('hide')
                toast({
                    type: 'success',
                    title: 'Course Created successfully'
                })
            })
            .catch(()=>{

            })            
            }
        },
        created() {
            this.LoadCourses();
            Fire.$on('CourseEvent',()=>this.LoadCourses())
        }
    }
</script>
