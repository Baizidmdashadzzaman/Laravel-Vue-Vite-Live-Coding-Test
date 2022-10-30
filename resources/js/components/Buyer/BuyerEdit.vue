<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Buyer Edit</h3>
                        <router-link :to="{name:'buyerlist'}"  class="btn btn-primary" style="float:right">Back</router-link>
                    </div>
                    <div class="card-body">
                            <form action="javascript:void(0)" @submit="submitdata" method="post" enctype="multipart/form-data">
                              <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Buyer name</label>
                                <input type="text" class="form-control" name="buyers_name" v-model="sdata.buyers_name" id="buyers_name" required>
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Buyer email</label>
                                <input type="email" class="form-control" name="buyers_email" v-model="sdata.buyers_email" id="buyers_email" required>
                              </div>
                              <button type="submit" class="btn btn-primary" >{{ processing ? "Please wait" : "Update" }}</button>
                            </form>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
    
    <script>
    export default {
        name:"buyeredit",
        data(){
            return {
                user:this.$store.state.auth.user,
                sdata:{
                    buyers_name:"",
                    buyers_email:"",
                },
                processing:false,
                loadingsms:false,
                loadingdata:false,
            }
        },
        created(){
            window.scrollTo(0, 0);
            this.getData();
        },
        methods:{
            getData()
            {
                this.loadingdata = true;
                axios
                .get(`/api/buyer-edit/${this.$route.params.id}`)
                .then((response) => { 
                    this.sdata.buyers_name = response.data.singledata.buyers_name;
                    this.sdata.buyers_email = response.data.singledata.buyers_email;
                    this.sdata.id = response.data.singledata.id;
                    this.loadingdata = false;
                });
            },
            submitdata(e)
            {
                e.preventDefault();
                this.processing = true;
                let currentObj = this;
                const config = 
                {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                let formData = new FormData();
                formData.append('buyers_name', this.sdata.buyers_name);
                formData.append('buyers_email', this.sdata.buyers_email);
                formData.append('id', this.sdata.id);
                axios.post('/api/buyer-update', formData, config)
                .then((res) => {
                    this.processing = false;
                    this.messages = res.data.msg;
                          if(res.data.status == 0)
                          {
                            this.$swal.fire({
                               icon: 'error',
                                text: res.data.message,
                                showConfirmButton: true,
                                timer: 6000
                             })
                          }
                          else
                          {
                              this.$swal.fire({
                                icon: 'success',
                                text: res.data.message,
                                showConfirmButton: true,
                                timer: 6000
                             })

                             this.$router.push({name:"buyerlist"})
                          }
                })
                .catch((error) => {
                    console.log(error);
               });
    
            }
        },
        mounted (){
            window.scrollTo(0, 0);
        },
    }
    </script>