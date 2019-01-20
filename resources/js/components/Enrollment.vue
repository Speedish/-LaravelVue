<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Enrollment List</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Enroll <i class="fas fa-sign-in-alt fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Student Id</th>
                    <th>Course Id</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="enrollment in enrollments" :key="enrollment.id">
                    <td>{{enrollment.id}}</td>
                    <td>{{enrollment.user.name }}</td>
                    
                    <td>{{enrollment.course.title}}</td>
                    <td>
                        <!-- <a href="#" @click="editModal(enrollments)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /-->
                        <a href="#" @click="deleteEnrollment(enrollment.id)">
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
                <h5 class="modal-title" v-show="!editMode" id="exampleModalLabel">Add New Enrollment</h5>
                <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Update Enrollment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateEnrollment() : CreateEnrollment()">
            <div class="modal-body">
                <div class="form-group">
                    <select name="user" v-model="form.user_id" id="user" placeholder="User Name" class="form-control" :class="{ 'is-invalid': form.errors.has('user') }">
                         <option v-for="item in users" :value="item.id" :key="item.id">{{item.name}}</option>
                    </select>
                    <has-error :form="form" field="userid"></has-error>
                </div>
                <div class="form-group">
                    <select name="course" v-model="form.course_id" id="course" class="form-control" :class="{ 'is-invalid': form.errors.has('course') }">
                        <option v-for="item in courses" :value="item.id" :key="item.id">{{item.title}}</option>
                    </select>
                    <has-error :form="form" field="course_id"></has-error>
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
                username:'',
                enrollments: {},
                users: {},
                courses: {},
                form: new Form({
                id:'',
                users: '',
                courses:''                
                })
            }
        },
        methods: {
            getUserName(id){
                let result = [];
                //username= axios.get("api/user/"+id)
                axios.get("api/user/"+id)
                .then(function (response) {
                    return (response.data.name);
                })
                .catch(function (error) {
                    console.log(error);
                });                           
            },
            updateEnrollment(){
                this.form.put('api/enrollment/'+this.form.id)
                .then(()=>{
                    $('#addNew').modal('hide')
                    swal(
                            'Updated!',
                            'Your record has been updated.',
                            'success'
                            )
                            Fire.$emit('EnrollmentEvent')
                })
                .catch(()=>{

                });
            },
            editModal(enrollment){
                this.editMode=true;
                this.form.reset();
                $('#addNew').modal('show')
                this.form.fill(enrollment)
            },
            newModal(){
                this.editMode=false;
                this.form.reset();
                $('#addNew').modal('show')
            },
            deleteEnrollment(id){
                swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Unenroll it!'
                    }).then((result) => {
                    if (result.value) {
                        this.form.delete('api/enrollment/'+id)
                        .then(()=>{                        
                            swal(
                            'Unenrolled!',
                            'Unenrolled successfully.',
                            'success'
                            )
                            Fire.$emit('EnrollmentEvent')                    
                        })
                        .catch(()=>{
                            swal("Failed","There was something wrong","warning");
                        });  
                    }                  
                    })
            },
            LoadUsers(){
                axios.get("api/user").then(({ data }) => (this.users=data.data))
            },
            LoadCourses(){
                axios.get("api/course").then(({ data }) => (this.courses=data.data))
            },
            LoadEnrollments(){
                axios.get("api/enrollment").then(({ data }) => (this.enrollments=data.data))
            },
            CreateEnrollment () {
            // Submit the form via a POST request
            this.form.post('api/enrollment')
            .then(()=>{
                Fire.$emit('EnrollmentEvent')
                $('#addNew').modal('hide')
                toast({
                    type: 'success',
                    title: 'Enrolled successfully'
                })
            })
            .catch(()=>{

            })            
            }
        },
        created() {
            this.LoadUsers();
            this.LoadCourses();
            this.LoadEnrollments();
            Fire.$on('EnrollmentEvent',()=>this.LoadEnrollments())
        }
    }
</script>
