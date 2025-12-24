cmake_minimum_required(VERSION 3.10)

set(PROJECT FaceController)
project(${PROJECT})

set(CMAKE_CXX_STANDARD 17)

find_package(OpenCV REQUIRED)
find_package(YARP REQUIRED)

include_directories(${OpenCV_INCLUDE_DIRS})

add_executable(${PROJECT} main.cpp)
target_link_libraries(${PROJECT}  
	${OpenCV_LIBS}
	${YARP_LIBRARIES}
)
