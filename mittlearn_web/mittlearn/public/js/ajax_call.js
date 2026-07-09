"use strict";
globalFunc.ajaxCall = function(path, post_data, call_type, b_send=null, success=null, error=null, complete=null) {
    $.ajax({
        url: base_url + "" + path,
        data: post_data,
        type: call_type,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        beforeSend: b_send,
        success: success,
        error: error,
        complete: complete
    })
};
globalFunc.before = function() {$('#loader').show()};
globalFunc.error = function() {$('#loader').hide()};
globalFunc.complete = function() {$('#loader').hide()};





globalFunc.listOfCompanies = function(data) {
    $('.comp_by_cat').html('');
    var listing = '';
        console.log(data.list);
    if(data.list.length>0){
        $.each(data.comp_list,function(key, data) {
            //var compLink = "";
            listing = listing+'<li role="presentation" style=""></li>';
        });
    } else {
        listing='No Records Found';  
    }
    $('.comp_by_cat').html(listing);
     $("#loading_image").hide();   
}

globalFunc.filterJobSuccess = function(data) {
    var listings_data_arr = data;        
            var listing = '';
            $(listings_data_arr).each(function(key, listings_data) {
                var posted_date_time = new Date(listings_data.posted_date * 1000);
                post_month     = posted_date_time.getMonth()+1;
                posted_date    = posted_date_time.getDate();
                var urlJob  = base_url+'job-details/'+listings_data.job_id;
              listing = listing+'<div class="row"><div class="col-xs-12 col-sm-12 right-section"> <div class="job-block block"> <div class="row flexbox"><div class="col-xs-3 col-sm-2 left"> <div class="div-table"> <div class="table-cell-div"> <img src="'+base_url+'company_logo/logo_thumbnail/'+listings_data.thumbnail+'" class="center-block" height="100px"> </div> </div> </div> <div class="col-xs-9 col-sm-10 right"> <a href="'+urlJob+'"><h2>'+listings_data.job_title.substring(0, 40)+'..</h2></a> <div class="clearfix jobs-time-location"> <div class="job-time">'+ listings_data.min_experience+'-'+listings_data.max_experience+' Years</div> <div class="job-location">'+listings_data.city_name+','+listings_data.country_name+'</div> </div> <div class="clearfix jobs-detail"> <div class="role"><strong>Job Role: </strong>'+listings_data.job_role.substring(0, 15)+'...</div> <div class="industry"><strong>Industry:</strong> '+listings_data.industry.substring(0, 15)+'...</div> <div class="career-label"><strong>Career Label:</strong> Mid</div> </div> </div></div> </div> </div></div>';
            });
            $('#content').html(listing);
            $("#loading_image").hide();
}


globalFunc.countOfJobInCountry = function(data) {
    $('.comp_by_cat').html('');
    var total_job = 0;
    if(data.total_jobs>0){
        total_job=data.total_jobs;
    }
    //$('.comp_by_cat').html(listing);
     $("#loading_image").hide();   
}

globalFunc.successUpdateCountry = function(data) {
      console.log(data);
  }

  