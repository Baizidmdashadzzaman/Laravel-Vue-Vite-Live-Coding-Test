<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Style Add</h3>
                        <router-link :to="{name:'stylelist'}"  class="btn btn-primary" style="float:right">Back</router-link>
                    </div>
                    <div class="card-body">
                            <form action="javascript:void(0)" @submit="submitdata" method="post" enctype="multipart/form-data">
                              <div class="row">
                                <div class="col-6">
                                  <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Search buyer</label>
                                    <input type="text" class="form-control" name="buyers_name" v-on:keyup="keymonitor" v-model="ThisIsField1"  id="buyers_name" placeholder="Enter buyer name to search">
                                  </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Select buyer</label>
                                        <select class="form-control" name="buyers_name" v-model="sdata.buyer_id"  id="buyers_name">
                                            <option value="0">--Select buyer--</option>
                                            <option v-for="singledata in allbuyer" :value="singledata.id">Name: {{singledata.buyers_name}} - Email: {{singledata.buyers_email}}</option>
                                        </select>
                                    </div>
                                </div>
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Style number</label>
                                <input type="text" class="form-control" name="style_number" v-model="sdata.style_number" id="style_number" required>
                              </div>
                              <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Color </label>
                                      <div v-for="singledata in colors" :value="singledata">
                                       <input type="checkbox" :id="singledata" :value="singledata" v-model="sdata.checkedColor">
                                       <label>&nbsp;{{singledata}}</label>
                                       &nbsp;&nbsp; 
                                      </div>
                              </div>
                               
                               <hr/>
                                <div class="card-body">
                                   <table class="table">
                                     <thead>
                                       <tr>
                                         <th scope="col">Meterial name</th>
                                         <th scope="col">Width</th>
                                         <th scope="col">Unit</th>
                                         <th scope="col">Size</th>
                                         <th scope="col">
                                            <a href="javascript:void(0)" @click="AddField" class="btn btn-primary">Add more</a>
                                         </th>
                                       </tr>
                                     </thead>
                                     <tbody>
                                        <tr v-for="(field,index) in fields" :key="index">
                                         <th scope="row">
                                            <input type="text" name="metarialname[]" v-model="metarialname[index]" class="form-control" />
                                         </th>
                                         <td>
                                            <input type="text" name="width[]" v-model="width[index]" class="form-control" />
                                         </td>
                                         <td>
                                            <input type="text" name="unit[]" v-model="unit[index]" class="form-control" />
                                         </td>
                                         <td>
                                            <input type="text" name="size[]" v-model="size[index]" class="form-control" />
                                         </td>
                                         <td>
                                            <div class="btn-group">
                                                <a href="javascript:void(0)" @click="removeField(field.indexNo)" class="btn btn-primary">Remove</a>
                                            </div>
                                         </td>
                                         </tr>
                                     </tbody>
                                   </table>
                                    
                                </div> 

                              <button type="submit" class="btn btn-primary" >{{ processing ? "Please wait" : "Submit" }}</button>
                            </form>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
    
    <script>
    export default {
        name:"styleadd",
        data(){
            return {
                user:this.$store.state.auth.user,
                sdata:{
                    buyer_id:0,
                    buyers_name:"",
                    buyers_email:"",
                    style_number:"",
                    checkedColor: [],
                },
                
                colors:['red','blue','green'],
                processing:false,
                loadingsms:false,
                loadingdata:false,
                allbuyer:[],
                ThisIsField1: '',
                search:{
                  query:''
                },
                indexNoInt:0,
                fields: [{ metarialname: '',width: '' ,unit: '' ,size: '' }],
                metarialname:[],
                width:[],
                unit:[],
                size:[],
            }
        },
        created(){
            window.scrollTo(0, 0);
            axios
                .get(`/api/buyer-list-all`)
                .then((response) => {
                    this.allbuyer = response.data.allData;
                    
            });
        },
        methods:{
            AddField: function () {
                this.indexNoInt = this.indexNoInt+1;
                this.fields.push({ metarialname: '',width: '' ,unit: '' ,size: '' });
            },
            removeField(key)
            {
                this.fields.splice(key, 1);
            },
            keymonitor() {
		        this.search.query = this.ThisIsField1;
                let uri = '/api/buyer-search-all';
                axios.post(uri, this.search).then((response) => {
                     this.allbuyer = response.data.allData; 
                })
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
                formData.append('buyer_id', this.sdata.buyer_id);
                formData.append('style_number', this.sdata.style_number);
                formData.append('checkedColor', this.sdata.checkedColor);
                formData.append('fields', this.fields);
                formData.append('metarialname', this.metarialname);
                formData.append('width', this.width);
                formData.append('unit', this.unit);
                formData.append('size', this.size);
                formData.append('size', this.size);
                axios.post('/api/style-store', formData, config)
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
                             this.$router.push({name:'stylelist'})
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