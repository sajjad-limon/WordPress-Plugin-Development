<?php

class DemoWidget extends WP_Widget {

	public function __construct()
    {
		parent::__construct(
			'widgetdemo',
			__( 'Demo Widget', 'widgetdemo' ),
			array( 'description' => __( 'Our Widget Description', 'widgetdemo' ) )
		);
	}

	public function form( $instance )
    {
		$title     = isset( $instance['title'] ) ? $instance['title'] : __( 'Demo Widget', 'widgetdemo' );
		$latitude  = isset( $instance['latitude'] ) ? $instance['latitude'] : 23.9;
		$longitude = isset( $instance['longitude'] ) ? $instance['longitude'] : 90.8;
		$email	   = isset( $instance['email'] ) ? $instance['email'] : '';
		?>


		<div class="dw-scetion dw-col-3">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title', 'widgetdemo' ) ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ) ?>"
					value="<?php echo esc_attr( $title ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>"><?php echo esc_html__( 'Latitude', 'widgetdemo' ) ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'latitude' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'latitude' ) ) ?>"
					value="<?php echo esc_attr( $latitude ); ?>">
			</p>
		</div>
		<div class="dw-section dw-col-3">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'Longitude' ) ); ?>"><?php echo esc_html__( 'Longitude', 'widgetdemo' ) ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'longitude' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'longitude' ) ) ?>"
					value="<?php echo esc_attr( $longitude ); ?>">
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'Email' ) ); ?>"><?php echo esc_html__( 'Email', 'widgetdemo' ) ?></label>
				<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'email' ) ) ?>"
					value="<?php echo esc_attr( $email ); ?>">
			</p>
		</div>
        
		<?php
	}

	public function widget( $args, $instance ) 
    {
		//print_r($args);

		echo $args['before_widget'];
		if ( isset( $instance['title'] ) && $instance['title'] != '' ) {

			echo $args['before_title'];

			echo apply_filters( 'widget_title', $instance['title'] );

			echo $args['after_title'];
			?>
            <div class="widgetdemo <?php echo esc_attr( $args['id'] ); ?>">
                <p>Latitude: <?php echo isset( $instance['latitude'] ) ? $instance['latitude'] : 'N/A'; ?></p>
                <p>Longitude: <?php echo isset( $instance['longitude'] ) ? $instance['longitude'] : 'N/A'; ?></p>
                <p>Email: <?php echo isset( $instance['email'] ) ? $instance['email'] : 'N/A'; ?></p>
            </div>
			<?php
		}
		echo $args['after_widget'];
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $new_instance;
		
		$email = $new_instance['email'];
		if( !is_email($email) ) {
			$instance['email'] = $old_instance['email'];
		}
		if( !is_numeric($new_instance['latitude']) ) {
			$instance['latitude'] = $old_instance['latitude'];
		}
		if( !is_numeric($new_instance['longitude']) ) {
			$instance['longitude'] = $old_instance['longitude'];
		}
		return $instance;
	}
	
}

