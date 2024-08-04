<template>
<!-- Component Start -->
	<div class="flex flex-col items-center h-full overflow-hidden text-gray-700 bg-gray-100 rounded">
		<a class="flex items-center w-full px-3 mt-3" href="#">
			<img class="w-10" :src="'/images/profile.png'" alt=""/>
			<span class="ml-2 text-sm font-bold">Hi Suraj</span>
		</a>
		<div class="w-full px-2">
			<div class="flex flex-col items-center w-full mt-3 border-t border-gray-300">
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/Home-Icon.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Dasboard</span>
				</a>
				<a class="relative flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/Calendar-Icons.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">My Booking</span>
					<span class="absolute top-0 left-0 w-2 h-2 mt-2 ml-2 bg-orange-500 rounded-full"></span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 bg-gray-300 rounded" href="#">
					<img class="w-6" :src="'/images/check-list.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">My Enquiry</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/booked.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Booking Calendar</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/wallet.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">My Wallet</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/ticket-flight.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Airline Markup</span>
				</a>
			</div>
			<div class="flex flex-col items-center w-full mt-2 border-t border-gray-300">
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/vacation.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Packages</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/activities--package.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Activities</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/hotel.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Hotels</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/taxi.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Cabs</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/man.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Rentals</span>
				</a>
			</div>
			<div class="flex flex-col items-center w-full mt-2 border-t border-gray-300">
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/gst-icon.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Manage GST</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/travel-agency.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Agent Info</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/user-icon.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">My Profile</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/heart.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">My Favourite</span>
				</a>
				<a class="flex items-center w-full h-12 px-3 mt-2 rounded hover:bg-gray-300" href="#">
					<img class="w-6" :src="'/images/padlock.png'" alt=""/>
					<span class="ml-2 text-sm font-medium">Change Password</span>
				</a>
			</div>
		</div>
		<a class="flex items-center justify-center w-full h-12 mt-auto bg-gray-200 hover:bg-gray-300" href="#">
			<img class="w-6" :src="'/images/logout.png'" alt=""/>
			<span class="ml-2 text-sm font-medium">Logout</span>
		</a>
	</div>
	<!-- Component End  -->
</template>