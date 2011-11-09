(function() {
	
	window.SM = function(config) {
		this._state = config;
	}
	
	function isArray(it) {
        return Object.prototype.toString.call(it) === "[object Array]";
    }
	
	SM.prototype = {
		
		name : 'SimpleModel',
		
		_subscribers : {
			
		},
		
		_state : {
			
		},
		
		when : function(key,callback,scope,args) {
			
			var subscriber = {
				key       : key,
				callback  : callback,
				scope     : scope,
				args : args
			};
			
			if(this._subscribers[key]) {
				this._subscribers[key].push(subscriber);
			} else {
				this._subscribers[key] = [subscriber];
			}
		
		},
		
		run : function(key,args) {
			
			var subscribers = this._subscribers[key];
			
			if( isArray( subscribers ) ) {
				
				for(var i=0, l=subscribers.length; l > i; i++) {
					var subscriber = subscribers[i],
					    scope      = subscriber.scope | window;
					
					subscriber.callback.apply(scope,[subscriber.args,args]);
				};
				
			}
			
		}
	}
	
})();