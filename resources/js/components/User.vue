<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">User List</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fa fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role Type</th>
                    <th>Registered Date</th>
                    <th>Modify</th>
                  </tr>
                  <tr v-for="user in users" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upText}}</td>
                    <td>{{user.created_at | viewDate}}</td>
                    <td>
                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUser(user.id)">
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
                <h5 class="modal-title" v-show="!editMode" id="exampleModalLabel">Add New User</h5>
                <h5 class="modal-title" v-show="editMode" id="exampleModalLabel">Update User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editMode ? updateUser() : CreateUser()">
            <div class="modal-body">
                <div class="form-group">
                    <input v-model="form.name" type="text" name="name" placeholder="Name"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                    <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.email" type="email" name="email" placeholder="Email Address"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                    <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                        <option value="">Select User Role</option>
                        <option value="admin">Admin</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="form-group">
                    <input v-model="form.password" type="password" name="password" placeholder="Password"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
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
                users: {},
                form: new Form({
                id:'',
                name: '',
                email:'',                
                password: '',
                type:'',
                photo: ''
                })
            }
        },
        methods: {
            updateUser(){
                this.form.put('api/user/'+this.form.id)
                .then(()=>{
                    $('#addNew').modal('hide')
                    swal(
                            'Updated!',
                            'Your record has been updated.',
                            'success'
                            )
                            Fire.$emit('UserEvent')
                })
                .catch(()=>{

                });
            },
            editModal(user){
                this.editMode=true;
                this.form.reset();
                $('#addNew').modal('show')
                this.form.fill(user)
            },
            newModal(){
                this.editMode=false;
                this.form.reset();
                $('#addNew').modal('show')
            },
            deleteUser(id){
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
                        this.form.delete('api/user/'+id)
                        .then(()=>{                        
                            swal(
                            'Deleted!',
                            'Your record has been deleted.',
                            'success'
                            )
                            Fire.$emit('UserEvent')                    
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
            CreateUser () {
            // Submit the form via a POST request
            this.form.post('api/user')
            .then(()=>{
                Fire.$emit('UserEvent')
                $('#addNew').modal('hide')
                toast({
                    type: 'success',
                    title: 'User Created successfully'
                })
            })
            .catch(()=>{

            })            
            }
        },
        created() {
            this.LoadUsers();
            Fire.$on('UserEvent',()=>this.LoadUsers())
        }
    }
</script>
