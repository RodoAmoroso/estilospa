class CRUD {

	constructor(_controller,_class){
		this.controller = _controller
		this.class = _class
	}

	get(filters={}){
		return new Promise((resolve,reject)=>{
			ajax(`${this.controller}/get`,{
				class:this.class,
				filters:filters
			})
				.then(response=>resolve(response))
				.catch(error=>reject(error))
		})
	}
	find(id){
		return new Promise((resolve,reject)=>{
			ajax(`${this.controller}/find/${id}`,{class:this.class})
				.then(response=>resolve(response))
				.catch(error=>reject(error))
		})
	}
	duplicate(id){
		return new Promise((resolve,reject)=>{
			ajax(`${this.controller}/duplicate/${id}`,{class:this.class})
				.then(response=>resolve(response))
				.catch(error=>reject(error))
		})
	}
	save(post){
		return new Promise((resolve,reject)=>{
			ajax(`${this.controller}/save`,post)
				.then(response=>{
					toastr['success'](response.message)
					resolve(response)
				})
				.catch(error=>reject(error))
		})
	}
	delete(id){
		return new Promise((resolve,reject)=>{
			ajax(`${this.controller}/delete/${id}`,{class:this.class})
				.then(response=>resolve(response))
				.catch(error=>reject(error))
		})
	}
	reorder(){
		let ids = []
		$.each(this.listing.find(`${this.module}`),(k,v)=>{
			ids.push($(v).attr('data-id'));
		});
		return new Promise((resolve,reject)=>{
			ajax(`${this.controller}/reorder`,{
				class:this.class,
				arrids:ids
			})
				.then(response=>resolve(response))
				.catch(error=>reject(error))
		})
	}

}