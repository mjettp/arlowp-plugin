<div class="arlo" id="arlo">
	[arlo_template_region_selector]
	[arlo_upcoming_event_filters filtertext='Filter' resettext="Reset" filters='category,month,location' wrap='<div id="filters" class="collapse">%s</div>']

	<button data-toggle="collapse" data-target="#filters" class="btn form-control full-width m-b-20 filter-toggle collapsed">
      <div class="display-filters">
        Display filters
      </div>

      <div class="hide-filters">
        Hide filters
      </div>
    </button>

    <div class="clearfix row-fix row">
		[arlo_upcoming_list_item limit="20"]
	    [arlo_group_divider wrap="<h2 class='m-b-30 clearfix sm-p-r-0 sm-p-l-0 p-r-15 p-l-15'>%s</h2>"]
		<div class="col-lg-4 col-md-6 col-xs-12 m-b-30 md-m-b-20 md-no-padding">
			<div class="event-card text-center has-label" href="#">
			  <div class="card-front bg-white h-v-centre-container drop-shadow">
			    <div class="event-content-wrapper">

			      <div class="event-content md-p-l-70 md-p-b-10 md-p-t-10 md-p-r-0 xl-p-r-20 xl-p-l-20 lg-p-r-20 lg-p-l-20 centered">

		            [arlo_event_template_permalink wrap='<a class="name" href="%s">']<span class="large-text">[arlo_event_template_name]</span></a>
			        <p class="text-primary text-uppercase location md-no-margin"><i class="icons8-marker"></i> [arlo_event_location]</p>
			      </div>

			      <div class="date padding-10">
			        <h4 class="day no-margin">[arlo_event_start_date format="%e"]</h4>
			        <h5 class="month no-margin">[arlo_event_start_date format="%b"]</h5>
			      </div>

			      
		          [arlo_event_price wrap='<div class="price padding-10 text-left">%s</div>']

			      <a class="btn-expand">
			        <i class="fa fa-angle-down" aria-hidden="true"></i>
			      </a>
			    </div>

			    <div class="collapse event-expandable">

			      <p>[arlo_event_start_date format="%A, %e %B %G"]</p>
			      <p class="muted">[arlo_event_start_date format="%I:%M %p"] - [arlo_event_end_date format="%I:%M %p"] ([arlo_event_duration])</p>

			      [arlo_event_provider wrap='<div class="m-t-10 m-b-10">Provided by %s</div>']

			      <div class="location m-t-10 m-b-10">
			        <div class="icon"><i class="icons8-marker"></i></div>
			        <ul class="address m-t-10 m-l-30 p-l-0">
			          <li>[arlo_event_location]</li>
			        </ul>
			      </div>

			      [arlo_event_presenters wrap='<div class="presenters m-t-10 m-b-10">Presented by <br>%s</div>']

			      [arlo_event_offers wrap='<div class="price">%s</div>']

			      [arlo_event_notice wrap='<strong>%s</strong>']

			      <div class="buttons">
			        [arlo_event_registration]
			        [arlo_event_template_permalink wrap='<a class="btn btn-secondary-alt" href="%s">More Information</a>']
			      </div>

			    </div> 

			  </div>

			  <div class="card-back bg-primary text-white">
			    <p class="date no-margin">[arlo_event_start_date format="%a %e %b %G"]</p>
			    <p class="time normal-text muted truncate-1">[arlo_event_start_date format="%I:%M %p"] - [arlo_event_end_date format="%I:%M %p"] ([arlo_event_duration])</p>
			    <p class="normal-text summary m-b-5 truncate-4">[arlo_event_template_summary]</p>
			    [arlo_event_registration][arlo_event_template_permalink wrap='<a class="btn btn-cons btn-bordered" href="%s">More Information</a>']
			    [arlo_event_provider wrap='<p class="small-text text-left muted text-white m-b-0 m-t-5">Provided by %s</p>']
			  </div>
			</div>
		</div>
		[arlo_event_rich_snippet]
		[/arlo_upcoming_list_item]
	</div>
	<div class="arlo-clear-both"></div>
	[arlo_no_event_text]
	[arlo_upcoming_list_pagination limit="20" wrap='<div class="arlo-pagination">%s</div>']
</div>

[arlo_powered_by]