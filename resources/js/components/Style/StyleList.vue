<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Style list</h3>
                        <router-link :to="{name:'styleadd'}"  class="btn btn-primary" style="float:right">Add</router-link>
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
                                 <th scope="col">Buyer name</th>
                                 <th scope="col">Style number</th>
                                 <th scope="col">Color</th>
                                 <th scope="col">Action</th>
                               </tr>
                             </thead>
                             <tbody>
                               <tr  v-for="singledata in alldata.data" :key="singledata" >
                                 <th scope="row">{{singledata.id}}</th>
                                 <td>{{singledata.buyer.buyers_name}}</td>
                                 <td>{{singledata.style_number}}</td>
                                 <td>{{singledata.color}}</td>
                                 <td>
                                    <div class="btn-group">
                                        <router-link :to="{name:'styleedit', params: { id: singledata.id }}"  class="btn btn-primary" >View</router-link>
                                        <!-- <router-link :to="{name:'styleadditems', params: { id: singledata.id }}"  class="btn btn-info" >Add item</router-link> -->
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
    name:"stylelist",
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
                .get(`/api/style-list`)
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
                .get('/api/style-list/?page=' + page)
                .then((response) => {
                    this.alldata = response.data.allData;
                    this.loadingpagedata = false;                
            });
			window.scrollTo(0, 0);
            
        },
       keymonitor() {
		    this.search.query = this.ThisIsField1;
            let uri = '/api/style-search';
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
                .get(`/api/style-delete/${id}`)
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