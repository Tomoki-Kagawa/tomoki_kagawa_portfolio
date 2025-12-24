cmake_minimum_required(VERSION 3.10.2)

set(PROJECT SerialCommunication)
set(CMAKE_AUTOMOC ON)
set(CMAKE_INCLUDE_CURRENT_DIR ON)
set(CMAKE_CXX_STANDARD 11)

project(${PROJECT})
find_package(Qt5Widgets REQUIRED)
find_package(YARP REQUIRED)

add_executable(${PROJECT} 
    main.cpp
    SerialCommunication.cpp
    SerialCommunication.h
)
qt5_use_modules(${PROJECT} Widgets)

target_link_libraries(${PROJECT}
    ${YARP_LIBRARIES}
)

install(TARGETS ${PROJECT}
    DESTINATION bin
)

