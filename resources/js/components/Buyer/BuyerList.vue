<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Buyer list</h3>
                        <router-link :to="{name:'buyeradd'}"  class="btn btn-primary" style="float:right">Add</router-link>
                        <div class="row g-3 align-items-center">
                          <div class="col-auto">
                            <label for="inputPassword6" class="col-form-label">Search</label>
                          </div>
                          <div class="col-auto">
                            <input type="text" name="table_search" v-on:keyup="keymonitor" v-model="ThisIsField1"  class="form-control" placeholder="Search..." >
                          </div>
                        </div>
                    </div>
                    <div class="card-body">
                           <table class="table">
                             <thead>
                               <tr>
                                 <th scope="col">ID</th>
                                 <th scope="col">Name</th>
                                 <th scope="col">Email</th>
                                 <th scope="col">Action</th>
                               </tr>
                             </thead>
                             <tbody>
                               <tr  v-for="singledata in alldata.data" :key="singledata" >
                                 <th scope="row">{{singledata.id}}</th>
                                 <td>{{singledata.buyers_name}}</td>
                                 <td>{{singledata.buyers_email}}</td>
                                 <td>
                                    <div class="btn-group">
                                        <router-link :to="{name:'buyeredit', params: { id: singledata.id }}"  class="btn btn-primary" >Edit</router-link>
                                        <a href="javascript:void(0)" @click="deleteData(singledata.id)" class="btn btn-danger">Delete</a>
                                    </div>
                                 </td>
                               </tr>
                             </tbody>
                           </table>
                            
                            <pagination :data="alldata" @pagination-change-page="getResults">
                                <template #prev-nav>
                                    <span>&lt;</span>
                                </template>
                                <template #next-nav>
                                    <span>&gt;</span>
                                </template>
                            </pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LaravelVuePagination from 'laravel-vue-pagination';
export default {
    name:"buyerlist",
    components: {
        'Pagination': LaravelVuePagination
    },
    data(){
        return {
            user:this.$store.state.auth.user,
            alldata:[],
            processing:false,
            ThisIsField1: '',
            search:{
              query:''
            },
            loadingpagedata:true
        }
    },
    created(){
        this.getResults();
    },
    mounted (){
        window.scrollTo(0, 0);
    },
    methods:{
       getData()
       {
            axios
                .get(`/api/buyer-list`)
                .then((response) => {
                    this.alldata = response.data.allData;
                    
            });
       },
       getResults(page) {
            this.loadingpagedata = true;
             if (typeof page === 'undefined') {
                 page = 1;
             }
			axios
                .get('/api/buyer-list/?page=' + page)
                .then((response) => {
                    this.alldata = response.data.allData;
                    this.loadingpagedata = false;                
            });
			window.scrollTo(0, 0);
            
        },
       keymonitor() {
		    this.search.query = this.ThisIsField1;
            let uri = '/api/buyer-search';
            axios.post(uri, this.search).then((response) => {
                 this.alldata = response.data.allData; 
            })
	   },
       deleteData(id)
       {
            this.$swal.fire({
               title: 'Are you sure?',
               text: "You won't be able to revert this!",
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, delete it!'
           }).then((result) => {
            if (result.isConfirmed) {
                 axios
                .get(`/api/buyer-delete/${id}`)
                .then((response) => {
                    if(response.data.status == 0)
                      {
                        this.$swal.fire({
                           icon: 'error',
                            text: response.data.message,
                            showConfirmButton: true,
                            timer: 6000
                         })
                        this.processing = false;
                      }
                      else
                      {
                         this.alldata = response.data.allData;
                         this.$swal.fire({
                           icon: 'success',
                            text: response.data.message,
                            showConfirmButton: true,
                            timer: 6000
                         })
                      
                      }
                      
                });
             }
            })
              
       }
    },
}
</script>